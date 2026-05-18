import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../theme/app_theme.dart';
import '../services/chamado_service.dart';
import '../models/chamado.dart';
import 'app_drawer.dart';

class ManageScreen extends StatefulWidget {
  const ManageScreen({super.key});

  @override
  State<ManageScreen> createState() => _ManageScreenState();
}

class _ManageScreenState extends State<ManageScreen> {
  late Future<List<Chamado>> _chamadosFuture;
  String _filterStatus = 'todos';

  @override
  void initState() {
    super.initState();
    _loadChamados();
  }

  void _loadChamados() {
    _chamadosFuture = context.read<ChamadoService>().getChamados();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      drawer: const AppDrawer(),
      appBar: AppBar(
        title: const Text('Meus Chamados'),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: Column(
        children: [
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Row(
                children: [
                  _buildFilterChip('Todos', 'todos'),
                  const SizedBox(width: 8),
                  _buildFilterChip('Pendente', 'pendente'),
                  const SizedBox(width: 8),
                  _buildFilterChip('Em Andamento', 'em_andamento'),
                  const SizedBox(width: 8),
                  _buildFilterChip('Concluído', 'concluido'),
                ],
              ),
            ),
          ),
          Expanded(
            child: FutureBuilder<List<Chamado>>(
              future: _chamadosFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }

                if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(
                    child: Text('Nenhum chamado encontrado'),
                  );
                }

                final chamados = snapshot.data!;
                final filtered = _filterStatus == 'todos'
                    ? chamados
                    : chamados
                        .where((c) => c.status == _filterStatus)
                        .toList();

                return ListView.builder(
                  itemCount: filtered.length,
                  itemBuilder: (context, index) {
                    return _buildChamadoCard(filtered[index]);
                  },
                );
              },
            ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => Navigator.of(context).pushNamed('/request'),
        backgroundColor: AppTheme.primaryColor,
        child: const Icon(Icons.add),
      ),
    );
  }

  Widget _buildFilterChip(String label, String value) {
    return FilterChip(
      label: Text(label),
      selected: _filterStatus == value,
      onSelected: (selected) {
        setState(() => _filterStatus = value);
      },
      selectedColor: AppTheme.primaryColor,
      labelStyle: TextStyle(
        color: _filterStatus == value ? Colors.white : AppTheme.textPrimaryColor,
      ),
    );
  }

  Widget _buildChamadoCard(Chamado chamado) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      child: ListTile(
        title: Text(chamado.descricao),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 4),
            Text('Local: ${chamado.local?.nome ?? "N/A"}'),
            Text('Tipo: ${chamado.tipoProblema?.nome ?? "N/A"}'),
          ],
        ),
        trailing: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: _getStatusColor(chamado.status),
                borderRadius: BorderRadius.circular(4),
              ),
              child: Text(
                chamado.displayStatus,
                style: const TextStyle(
                  fontSize: 12,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
            ),
          ],
        ),
        onTap: () {
          showChamadoDetails(chamado);
        },
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'pendente':
        return Colors.grey;
      case 'em_andamento':
        return Colors.orange;
      case 'concluido':
        return Colors.green;
      case 'cancelado':
        return Colors.red;
      default:
        return Colors.blue;
    }
  }

  void showChamadoDetails(Chamado chamado) {
    showModalBottomSheet(
      context: context,
      builder: (context) => Container(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Detalhes do Chamado',
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 16),
            Text('Descrição: ${chamado.descricao}'),
            const SizedBox(height: 8),
            Text('Local: ${chamado.local?.nome ?? "N/A"}'),
            const SizedBox(height: 8),
            Text('Tipo: ${chamado.tipoProblema?.nome ?? "N/A"}'),
            const SizedBox(height: 8),
            Text('Status: ${chamado.displayStatus}'),
            const SizedBox(height: 8),
            Text('Prioridade: ${chamado.displayPrioridade}'),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () => Navigator.pop(context),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppTheme.primaryColor,
                ),
                child: const Text('Fechar'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
