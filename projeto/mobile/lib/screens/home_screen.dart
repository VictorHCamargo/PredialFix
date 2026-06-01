import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import '../theme/app_theme.dart';
import '../services/chamado_service.dart';
import '../models/chamado.dart';
import 'app_drawer.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _totalChamados = 0;
  int _emAndamento = 0;
  int _concluidos = 0;
  List<Chamado> _chamadasRecentes = [];
  bool _isLoading = true;
  bool _loaded = false;
  String? _errorMessage;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_loaded) {
      _loaded = true;
      _loadData(context.read<ChamadoService>());
    }
  }

  Future<void> _loadData(ChamadoService chamadoService) async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final chamados = await chamadoService.getChamados();
      if (!mounted) return;

      setState(() {
        _totalChamados = chamados.length;
        _emAndamento = chamados.where((c) => c.status.toLowerCase() == 'em_andamento').length;
        _concluidos = chamados.where((c) => c.status.toLowerCase() == 'concluido').length;
        _chamadasRecentes = chamados.take(3).toList();
        _isLoading = false;
      });
    } catch (_) {
      if (!mounted) return;

      setState(() {
        _errorMessage = 'Erro ao carregar chamados';
        _isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao carregar chamados')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: const AppDrawer(),
      appBar: AppBar(title: const Text('Home'), elevation: 4),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _errorMessage != null
              ? Center(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(_errorMessage!),
                      const SizedBox(height: 12),
                      ElevatedButton(
                        onPressed: () =>
                            _loadData(context.read<ChamadoService>()),
                        child: const Text('Tentar novamente'),
                      ),
                    ],
                  ),
                )
              : SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Grid de Cards com Estatísticas
            Padding(
              padding: const EdgeInsets.all(16),
              child: GridView.count(
                crossAxisCount: 2,
                crossAxisSpacing: 12,
                mainAxisSpacing: 12,
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                children: [
                  _buildStatCard(
                    title: 'Total',
                    value: '$_totalChamados',
                    icon: Icons.list_alt,
                    color: AppTheme.primaryColor,
                  ),
                  _buildStatCard(
                    title: 'Em Andamento',
                    value: '$_emAndamento',
                    icon: Icons.hourglass_bottom,
                    color: Colors.orange,
                  ),
                  _buildStatCard(
                    title: 'Concluídos',
                    value: '$_concluidos',
                    icon: Icons.check_circle,
                    color: Colors.green,
                  ),
                  _buildStatCard(
                    title: 'Pendentes',
                    value: '${_totalChamados - _emAndamento - _concluidos}',
                    icon: Icons.pending_actions,
                    color: Colors.red,
                  ),
                ],
              ),
            ),

            // Seção Chamados Recentes
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 8, 16, 16),
              child: const Text(
                'Chamados Recentes',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: AppTheme.textPrimaryColor,
                ),
              ),
            ),

            // Lista de Chamados Recentes
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: _chamadasRecentes.isEmpty
                  ? const Center(
                      child: Text('Nenhum chamado encontrado'),
                    )
                  : Column(
                      children: [
                        for (var chamado in _chamadasRecentes)
                          Padding(
                            padding: const EdgeInsets.only(bottom: 12),
                            child: _buildChamadoCard(chamado),
                          ),
                      ],
                    ),
            ),

            const SizedBox(height: 24),
          ],
        ),
      ),
    );
  }

  Widget _buildStatCard({
    required String title,
    required String value,
    required IconData icon,
    required Color color,
  }) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.1),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: color.withOpacity(0.1),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Icon(icon, color: color, size: 24),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppTheme.textSecondaryColor,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  value,
                  style: const TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textPrimaryColor,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildChamadoCard(Chamado chamado) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(8),
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.1),
            blurRadius: 4,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: Text(
                  chamado.descricao,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: AppTheme.textPrimaryColor,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: _getStatusColor(chamado.status).withOpacity(0.2),
                  borderRadius: BorderRadius.circular(4),
                ),
                child: Text(
                  chamado.displayStatus,
                  style: TextStyle(
                    fontSize: 11,
                    fontWeight: FontWeight.w600,
                    color: _getStatusColor(chamado.status),
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                chamado.local?.nome ?? 'Local não informado',
                style: const TextStyle(
                  fontSize: 12,
                  color: AppTheme.textSecondaryColor,
                ),
              ),
              Text(
                DateFormat('dd/MM/yyyy').format(chamado.dataAbertura),
                style: const TextStyle(
                  fontSize: 12,
                  color: AppTheme.textSecondaryColor,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status.toLowerCase()) {
      case 'em_andamento':
      case 'em andamento':
        return Colors.orange;
      case 'concluido':
      case 'concluído':
        return Colors.green;
      case 'pendente':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }
}
