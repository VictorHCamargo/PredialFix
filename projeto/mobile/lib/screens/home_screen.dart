import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import '../theme/app_theme.dart';
import '../services/chamado_service.dart';
import '../services/auth_service.dart';
import '../models/user.dart';
import 'app_drawer.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late Future<void> _loadDataFuture;
  User? _currentUser;
  int _totalChamados = 0;
  int _emAndamento = 0;
  int _concluidos = 0;

  @override
  void initState() {
    super.initState();
    _loadDataFuture = _loadData();
  }

  Future<void> _loadData() async {
    final authService = context.read<AuthService>();
    final chamadoService = context.read<ChamadoService>();

    _currentUser = await authService.getCurrentUser();
    
    try {
      final chamados = await chamadoService.getChamados();
      setState(() {
        _totalChamados = chamados.length;
        _emAndamento = chamados.where((c) => c.status == 'em_andamento').length;
        _concluidos = chamados.where((c) => c.status == 'concluido').length;
      });
    } catch (e) {
      print('Erro ao carregar chamados: \$e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: const AppDrawer(currentPage: MenuPage.home),
      appBar: AppBar(title: const Text('Home'), elevation: 4),
      body: SingleChildScrollView(
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
              child: Column(
                children: [
                  _buildChamadoRecenteCard(
                    tipo: 'Tipo',
                    titulo: 'Tomada em Curto Circuito',
                    localizacao: 'Bloco A, Sala 1',
                    data: '02/01/2026',
                    status: 'Elétrica',
                    statusColor: AppTheme.primaryColor,
                  ),
                  const SizedBox(height: 12),
                  _buildChamadoRecenteCard(
                    tipo: 'Tipo',
                    titulo: 'Tomada em Curto Circuito',
                    localizacao: 'Bloco A, Sala 1',
                    data: '02/01/2026',
                    status: 'Elétrica',
                    statusColor: AppTheme.primaryColor,
                  ),
                  const SizedBox(height: 12),
                  _buildChamadoRecenteCard(
                    tipo: 'Tipo',
                    titulo: 'Tomada em Curto Circuito',
                    localizacao: 'Bloco A, Sala 1',
                    data: '02/01/2026',
                    status: 'Elétrica',
                    statusColor: AppTheme.primaryColor,
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

  Widget _buildActionButton({
    required String title,
    required IconData icon,
    required VoidCallback onPressed,
  }) {
    return ElevatedButton.icon(
      onPressed: onPressed,
      icon: Icon(icon, size: 20),
      label: Text(title),
      style: ElevatedButton.styleFrom(
        backgroundColor: AppTheme.primaryColor,
        padding: const EdgeInsets.symmetric(vertical: 12),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(8),
        ),
      ),
    );
  }
}
