import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/chamado.dart';
import '../models/feedback.dart' as FeedbackModel;
import '../services/chamado_service.dart';
import '../services/feedback_service.dart';
import '../theme/app_theme.dart';
import 'app_drawer.dart';

class RatingScreen extends StatefulWidget {
  const RatingScreen({super.key});

  @override
  State<RatingScreen> createState() => _RatingScreenState();
}

class _RatingScreenState extends State<RatingScreen> {
  late ChamadoService _chamadoService;
  late FeedbackService _feedbackService;

  List<Chamado> _chamados = [];
  List<FeedbackModel.Feedback> _feedbacks = [];
  bool _isLoading = true;
  bool _loaded = false;
  String? _errorMessage;

  List<Chamado> get _chamadosParaAvaliar {
    final avaliados = _feedbacks.map((f) => f.idChamado).toSet();
    return _chamados
        .where((c) => c.status == 'concluido' && !avaliados.contains(c.id))
        .toList();
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_loaded) {
      _loaded = true;
      _chamadoService = context.read<ChamadoService>();
      _feedbackService = context.read<FeedbackService>();
      _loadData();
    }
  }

  Future<void> _loadData() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final chamados = await _chamadoService.getChamados();
      final feedbacks = await _feedbackService.getFeedbacks();
      if (!mounted) return;
      setState(() {
        _chamados = chamados;
        _feedbacks = feedbacks;
        _isLoading = false;
      });
    } catch (_) {
      if (!mounted) return;
      setState(() {
        _errorMessage = 'Erro ao carregar avaliações';
        _isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao carregar avaliações')),
      );
    }
  }

  Future<void> _showFeedbackDialog({
    required Chamado chamado,
    FeedbackModel.Feedback? feedback,
  }) async {
    final comentarioController = TextEditingController(
      text: feedback?.comentario ?? '',
    );
    final screenContext = context;
    var rating = feedback?.classificacao ?? 0;
    var isSaving = false;

    try {
      await showDialog(
        context: context,
        builder: (dialogContext) => StatefulBuilder(
          builder: (context, setDialogState) {
            return AlertDialog(
              title: Text(feedback == null ? 'Nova Avaliação' : 'Editar Avaliação'),
              content: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      chamado.descricao,
                      style: const TextStyle(fontWeight: FontWeight.w600),
                    ),
                    const SizedBox(height: 16),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: List.generate(
                        5,
                        (index) => IconButton(
                          onPressed: isSaving
                              ? null
                              : () {
                                  setDialogState(() => rating = index + 1);
                                },
                          icon: Icon(
                            Icons.star,
                            color: (index + 1) <= rating
                                ? Colors.amber
                                : Colors.grey,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: comentarioController,
                      enabled: !isSaving,
                      maxLines: 3,
                      decoration: const InputDecoration(
                        labelText: 'Comentário',
                      ),
                    ),
                  ],
                ),
              ),
              actions: [
                TextButton(
                  onPressed: isSaving
                      ? null
                      : () => Navigator.of(dialogContext).pop(),
                  child: const Text('Cancelar'),
                ),
                ElevatedButton(
                  onPressed: isSaving || rating == 0
                      ? null
                      : () async {
                          final navigator = Navigator.of(dialogContext);
                          final messenger = ScaffoldMessenger.of(screenContext);
                          setDialogState(() => isSaving = true);

                          final saved = feedback == null
                              ? await _feedbackService.createFeedback(
                                  idChamado: chamado.id,
                                  avaliacao: rating,
                                  comentario: comentarioController.text
                                          .trim()
                                          .isEmpty
                                      ? null
                                      : comentarioController.text.trim(),
                                )
                              : await _feedbackService.updateFeedback(
                                  id: feedback.id,
                                  avaliacao: rating,
                                  comentario: comentarioController.text
                                          .trim()
                                          .isEmpty
                                      ? null
                                      : comentarioController.text.trim(),
                                );

                          if (!mounted) return;

                          if (saved != null) {
                            navigator.pop();
                            await _loadData();
                            messenger.showSnackBar(
                              const SnackBar(
                                content: Text('Avaliação salva!'),
                                backgroundColor: Colors.green,
                              ),
                            );
                          } else {
                            setDialogState(() => isSaving = false);
                            messenger.showSnackBar(
                              const SnackBar(
                                content: Text('Erro ao salvar avaliação'),
                              ),
                            );
                          }
                        },
                  child: isSaving
                      ? const SizedBox(
                          width: 18,
                          height: 18,
                          child: CircularProgressIndicator(strokeWidth: 2),
                        )
                      : const Text('Salvar'),
                ),
              ],
            );
          },
        ),
      );
    } finally {
      comentarioController.dispose();
    }
  }

  Future<void> _deleteFeedback(FeedbackModel.Feedback feedback) async {
    final messenger = ScaffoldMessenger.of(context);
    final success = await _feedbackService.deleteFeedback(feedback.id);
    if (!mounted) return;

    if (success) {
      await _loadData();
      messenger.showSnackBar(
        const SnackBar(
          content: Text('Avaliação removida!'),
          backgroundColor: Colors.green,
        ),
      );
    } else {
      messenger.showSnackBar(
        const SnackBar(content: Text('Erro ao remover avaliação')),
      );
    }
  }

  Chamado? _findChamado(int idChamado) {
    for (final chamado in _chamados) {
      if (chamado.id == idChamado) return chamado;
    }
    return null;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      drawer: const AppDrawer(),
      appBar: AppBar(
        title: const Text('Avaliações'),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _errorMessage != null
          ? _buildErrorState()
          : RefreshIndicator(
              onRefresh: _loadData,
              child: ListView(
                padding: const EdgeInsets.all(16),
                children: [
                  _buildPendingSection(),
                  const SizedBox(height: 24),
                  _buildRegisteredSection(),
                ],
              ),
            ),
    );
  }

  Widget _buildErrorState() {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(_errorMessage!, textAlign: TextAlign.center),
            const SizedBox(height: 12),
            ElevatedButton(
              onPressed: _loadData,
              child: const Text('Tentar novamente'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPendingSection() {
    return _buildSection(
      title: 'Criar Nova Avaliação',
      emptyText: 'Nenhum chamado concluído para avaliar',
      children: _chamadosParaAvaliar
          .map(
            (chamado) => Card(
              margin: const EdgeInsets.only(bottom: 12),
              child: ListTile(
                title: Text(chamado.descricao),
                subtitle: Text(chamado.local?.nome ?? 'Local não informado'),
                trailing: const Icon(Icons.star),
                onTap: () => _showFeedbackDialog(chamado: chamado),
              ),
            ),
          )
          .toList(),
    );
  }

  Widget _buildRegisteredSection() {
    return _buildSection(
      title: 'Avaliações Registradas',
      emptyText: 'Nenhuma avaliação registrada',
      children: _feedbacks.map(_buildFeedbackCard).toList(),
    );
  }

  Widget _buildFeedbackCard(FeedbackModel.Feedback feedback) {
    final chamado = _findChamado(feedback.idChamado);

    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: ListTile(
        title: Text(chamado?.descricao ?? 'Chamado #${feedback.idChamado}'),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 4),
            Row(
              children: List.generate(
                5,
                (index) => Icon(
                  Icons.star,
                  size: 18,
                  color: (index + 1) <= feedback.classificacao
                      ? Colors.amber
                      : Colors.grey[300],
                ),
              ),
            ),
            if (feedback.comentario != null &&
                feedback.comentario!.isNotEmpty) ...[
              const SizedBox(height: 4),
              Text(feedback.comentario!),
            ],
          ],
        ),
        trailing: PopupMenuButton<String>(
          onSelected: (value) {
            if (value == 'editar' && chamado != null) {
              _showFeedbackDialog(chamado: chamado, feedback: feedback);
            }
            if (value == 'excluir') {
              _deleteFeedback(feedback);
            }
          },
          itemBuilder: (context) => const [
            PopupMenuItem(value: 'editar', child: Text('Editar')),
            PopupMenuItem(value: 'excluir', child: Text('Excluir')),
          ],
        ),
      ),
    );
  }

  Widget _buildSection({
    required String title,
    required String emptyText,
    required List<Widget> children,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 12),
        if (children.isEmpty)
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(16),
            decoration: AppTheme.getCardDecoration(borderRadius: 8),
            child: Text(
              emptyText,
              style: const TextStyle(color: AppTheme.textSecondaryColor),
            ),
          )
        else
          ...children,
      ],
    );
  }
}
