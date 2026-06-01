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
  List<Chamado> _chamados = [];
  bool _isLoading = true;
  bool _loaded = false;
  String? _selectedStatus;
  String? _errorMessage;
  late ChamadoService _chamadoService;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_loaded) {
      _loaded = true;
      _chamadoService = context.read<ChamadoService>();
      _loadChamados();
    }
  }

  Future<void> _loadChamados() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final chamados = await _chamadoService.getChamados();
      if (!mounted) return;
      setState(() {
        _chamados = chamados;
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

  List<Chamado> get _filteredChamados {
    if (_selectedStatus == null || _selectedStatus == 'todos') {
      return _chamados;
    }
    return _chamados
        .where((c) => c.status.toLowerCase() == _selectedStatus!.toLowerCase())
        .toList();
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
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              child: Column(
                children: [
                  if (_errorMessage != null)
                    Padding(
                      padding: const EdgeInsets.fromLTRB(16, 16, 16, 0),
                      child: _buildErrorMessage(_errorMessage!),
                    ),

                  // Estatísticas
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Padding(
                      padding: const EdgeInsets.all(12),
                      child: Row(
                        children: [
                          _buildStatCard(
                            title: 'Total',
                            value: '${_chamados.length}',
                            icon: Icons.list_alt,
                            color: AppTheme.primaryColor,
                          ),
                          const SizedBox(width: 12),
                          _buildStatCard(
                            title: 'Em Andamento',
                            value:
                                '${_chamados.where((c) => c.status.toLowerCase() == 'em_andamento').length}',
                            icon: Icons.hourglass_bottom,
                            color: Colors.orange,
                          ),
                          const SizedBox(width: 12),
                          _buildStatCard(
                            title: 'Concluídos',
                            value:
                                '${_chamados.where((c) => c.status.toLowerCase() == 'concluido').length}',
                            icon: Icons.check_circle,
                            color: Colors.green,
                          ),
                          const SizedBox(width: 12),
                          _buildStatCard(
                            title: 'Pendentes',
                            value:
                                '${_chamados.where((c) => c.status.toLowerCase() == 'pendente').length}',
                            icon: Icons.pending_actions,
                            color: Colors.red,
                          ),
                        ],
                      ),
                    ),
                  ),

                  // Filtros
                  Padding(
                    padding: const EdgeInsets.fromLTRB(16, 8, 16, 16),
                    child: Container(
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(8),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withValues(alpha: 0.05),
                            blurRadius: 4,
                            offset: const Offset(0, 2),
                          ),
                        ],
                      ),
                      padding: const EdgeInsets.all(12),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text(
                            'Filtrar por Status',
                            style: TextStyle(
                              fontSize: 14,
                              fontWeight: FontWeight.w600,
                              color: AppTheme.textPrimaryColor,
                            ),
                          ),
                          const SizedBox(height: 12),
                          DropdownButtonFormField<String>(
                            value: _selectedStatus,
                            decoration: InputDecoration(
                              labelText: 'Status',
                              filled: true,
                              fillColor: Colors.grey[100],
                              contentPadding: const EdgeInsets.symmetric(
                                horizontal: 12,
                                vertical: 12,
                              ),
                              border: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(6),
                                borderSide: BorderSide.none,
                              ),
                              labelStyle: const TextStyle(fontSize: 12),
                            ),
                            items: const [
                              DropdownMenuItem<String>(
                                value: 'todos',
                                child: Text('Todos'),
                              ),
                              DropdownMenuItem<String>(
                                value: 'pendente',
                                child: Text('Pendente'),
                              ),
                              DropdownMenuItem<String>(
                                value: 'em_andamento',
                                child: Text('Em Andamento'),
                              ),
                              DropdownMenuItem<String>(
                                value: 'concluido',
                                child: Text('Concluído'),
                              ),
                            ],
                            onChanged: (value) {
                              setState(() => _selectedStatus = value);
                            },
                            isExpanded: true,
                          ),
                        ],
                      ),
                    ),
                  ),

                  // Chamados
                  Padding(
                    padding: const EdgeInsets.fromLTRB(16, 8, 16, 16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Chamados',
                          style: TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                            color: AppTheme.textPrimaryColor,
                          ),
                        ),
                        const SizedBox(height: 12),
                        _filteredChamados.isEmpty
                            ? const Center(
                                child: Text('Nenhum chamado encontrado'),
                              )
                            : Column(
                                children: [
                                  for (var chamado in _filteredChamados)
                                    Padding(
                                      padding: const EdgeInsets.only(
                                        bottom: 12,
                                      ),
                                      child: _buildChamadoItem(chamado),
                                    ),
                                ],
                              ),
                      ],
                    ),
                  ),

                  // Botão Novo Chamado
                  Padding(
                    padding: const EdgeInsets.all(16),
                    child: SizedBox(
                      width: double.infinity,
                      height: 48,
                      child: ElevatedButton(
                        onPressed: () =>
                            Navigator.pushNamed(context, '/request'),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppTheme.primaryColor,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
                        child: const Text(
                          'Novo Chamado',
                          style: TextStyle(
                            color: Colors.white,
                            fontWeight: FontWeight.w600,
                            fontSize: 14,
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
    );
  }

  Widget _buildErrorMessage(String message) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.red.withValues(alpha: 0.1),
        border: Border.all(color: Colors.red),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Text(message, style: const TextStyle(color: Colors.red)),
    );
  }

  Widget _buildStatCard({
    required String title,
    required String value,
    required IconData icon,
    required Color color,
  }) {
    return Container(
      width: 120,
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: color.withValues(alpha: 0.3)),
      ),
      child: Column(
        children: [
          Icon(icon, color: color, size: 20),
          const SizedBox(height: 8),
          Text(
            value,
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: color,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            title,
            style: const TextStyle(
              fontSize: 11,
              color: AppTheme.textSecondaryColor,
            ),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }

  Widget _buildChamadoItem(Chamado chamado) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(8),
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withValues(alpha: 0.1),
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
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      chamado.descricao,
                      style: const TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w600,
                        color: AppTheme.textPrimaryColor,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      chamado.local?.nome ?? 'Local não informado',
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppTheme.textSecondaryColor,
                      ),
                    ),
                  ],
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: _getStatusColor(chamado.status).withValues(alpha: 0.2),
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
