import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../theme/app_theme.dart';
import '../services/chamado_service.dart';
import '../services/feedback_service.dart';
import '../models/chamado.dart';
import 'app_drawer.dart';

class RatingScreen extends StatefulWidget {
  const RatingScreen({super.key});

  @override
  State<RatingScreen> createState() => _RatingScreenState();
}

class _RatingScreenState extends State<RatingScreen> {
  late Future<List<Chamado>> _chamadosFuture;
  int? _selectedRating;
  String _selectedComment = '';

  @override
  void initState() {
    super.initState();
    _chamadosFuture = context.read<ChamadoService>().getChamados();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      drawer: const AppDrawer(),
      appBar: AppBar(
        title: const Text('Avaliar Chamados'),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: FutureBuilder<List<Chamado>>(
        future: _chamadosFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }

          if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(
              child: Text('Nenhum chamado para avaliar'),
            );
          }

          final chamados = snapshot.data!
              .where((c) => c.status == 'concluido')
              .toList();

          if (chamados.isEmpty) {
            return const Center(
              child: Text('Nenhum chamado concluído para avaliar'),
            );
          }

          return ListView.builder(
            itemCount: chamados.length,
            itemBuilder: (context, index) {
              return _buildRatingCard(chamados[index]);
            },
          );
        },
      ),
    );
  }

  Widget _buildRatingCard(Chamado chamado) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      child: ExpansionTile(
        title: Text(chamado.descricao),
        subtitle: Text('Local: \${chamado.local?.nome ?? "N/A"}'),
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Sua Avaliação:',
                  style: TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 12),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                  children: List.generate(
                    5,
                    (index) => GestureDetector(
                      onTap: () => setState(() => _selectedRating = index + 1),
                      child: Icon(
                        Icons.star,
                        size: 32,
                        color: (index + 1) <= (_selectedRating ?? 0)
                            ? Colors.amber
                            : Colors.grey,
                      ),
                    ),
                  ),
                ),
                const SizedBox(height: 16),
                const Text(
                  'Comentário (opcional):',
                  style: TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 8),
                TextField(
                  maxLines: 3,
                  onChanged: (value) => _selectedComment = value,
                  decoration: InputDecoration(
                    hintText: 'Digite seu comentário...',
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                ),
                const SizedBox(height: 16),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: _selectedRating == null
                        ? null
                        : () => _submitRating(chamado),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppTheme.primaryColor,
                    ),
                    child: const Text('Enviar Avaliação'),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Future<void> _submitRating(Chamado chamado) async {
    final feedbackService = context.read<FeedbackService>();

    try {
      final feedback = await feedbackService.createFeedback(
        idChamado: chamado.id,
        avaliacao: _selectedRating!,
        comentario: _selectedComment.isNotEmpty ? _selectedComment : null,
      );

      if (!mounted) return;

      if (feedback != null) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Avaliação enviada com sucesso!'),
            backgroundColor: Colors.green,
          ),
        );
        setState(() {
          _selectedRating = null;
          _selectedComment = '';
        });
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Erro ao enviar avaliação'),
            backgroundColor: Colors.red,
          ),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Erro: \${e.toString()}'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }
}
