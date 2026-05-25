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
                  _buildStatCard(
                    'Chamados Féitos',
                    '32',
                    AppTheme.primaryColor,
                  ),
                  _buildStatCard(
                    'Chamados Féitos',
                    '32',
                    AppTheme.primaryColor,
                  ),
                  _buildStatCard(
                    'Chamados Féitos',
                    '32',
                    AppTheme.primaryColor,
                  ),
                  _buildStatCard(
                    'Chamados Féitos',
                    '32',
                    AppTheme.primaryColor,
                  ),
                ],
              ),
            ),

            // Seção de Filtros
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 8, 16, 16),
              child: Container(
                decoration: BoxDecoration(
                  color: AppTheme.cardBackgroundColor,
                  borderRadius: BorderRadius.circular(8),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.05),
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
                      'Filtrar',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w600,
                        color: AppTheme.textPrimaryColor,
                      ),
                    ),
                    const SizedBox(height: 12),
                    Row(
                      children: [
                        Expanded(
                          child: DropdownButtonFormField<String>(
                            value: _selectedLocal,
                            decoration: InputDecoration(
                              labelText: 'Local',
                              filled: true,
                              fillColor: AppTheme.inputBackgroundColor,
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
                            items: _localOptions
                                .map(
                                  (option) => DropdownMenuItem<String>(
                                    value: option,
                                    child: Text(option),
                                  ),
                                )
                                .toList(),
                            onChanged: (value) {
                              setState(() => _selectedLocal = value);
                            },
                            isExpanded: true,
                          ),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: DropdownButtonFormField<String>(
                            value: _selectedTipo,
                            decoration: InputDecoration(
                              labelText: 'Tipo',
                              filled: true,
                              fillColor: AppTheme.inputBackgroundColor,
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
                            items: _tipoOptions
                                .map(
                                  (option) => DropdownMenuItem<String>(
                                    value: option,
                                    child: Text(option),
                                  ),
                                )
                                .toList(),
                            onChanged: (value) {
                              setState(() => _selectedTipo = value);
                            },
                            isExpanded: true,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    DropdownButtonFormField<String>(
                      value: _selectedStatus,
                      decoration: InputDecoration(
                        labelText: 'Status',
                        filled: true,
                        fillColor: AppTheme.inputBackgroundColor,
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
                      items: _statusOptions
                          .map(
                            (option) => DropdownMenuItem<String>(
                              value: option,
                              child: Text(option),
                            ),
                          )
                          .toList(),
                      onChanged: (value) {
                        setState(() => _selectedStatus = value);
                      },
                      isExpanded: true,
                    ),
                  ],
                ),
              ),
            ),

            // Seção Chamados Recentes
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 8, 16, 16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text(
                        'Chamados Recentes',
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: AppTheme.textPrimaryColor,
                        ),
                      ),
                      GestureDetector(
                        onTap: () {},
                        child: const Icon(
                          Icons.edit,
                          color: AppTheme.primaryColor,
                          size: 18,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),

            // Lista de Chamados
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(
                children: [
                  _buildChamadoCard(
                    tipo: 'Tipo',
                    titulo: 'Tomada em Curto Circuito',
                    localizacao: 'Bloco A, Sala 1',
                    data: '02/01/2026',
                    status: 'Em Andamento',
                  ),
                  const SizedBox(height: 12),
                  _buildChamadoCard(
                    tipo: 'Tipo',
                    titulo: 'Tomada em Curto Circuito',
                    localizacao: 'Bloco A, Sala 1',
                    data: '02/01/2026',
                    status: 'Em Andamento',
                  ),
                  const SizedBox(height: 12),
                  _buildChamadoCard(
                    tipo: 'Tipo',
                    titulo: 'Tomada em Curto Circuito',
                    localizacao: 'Bloco A, Sala 1',
                    data: '02/01/2026',
                    status: 'Em Andamento',
                  ),
                ],
              ),
            ),

            // Botão Relatar novo Problema
            Padding(
              padding: const EdgeInsets.all(16),
              child: SizedBox(
                width: double.infinity,
                height: 48,
                child: ElevatedButton(
                  onPressed: () => Navigator.pushNamed(context, '/request'),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppTheme.primaryColor,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: const Text(
                    'Relatar novo Problema',
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
