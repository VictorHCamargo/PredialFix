import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import 'app_drawer.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

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
                    'Chamados Feitos',
                    '32',
                    AppTheme.primaryColor,
                  ),
                  _buildStatCard(
                    'Chamados Feitos',
                    '32',
                    AppTheme.primaryColor,
                  ),
                  _buildStatCard(
                    'Chamados Feitos',
                    '32',
                    AppTheme.primaryColor,
                  ),
                  _buildStatCard(
                    'Chamados Feitos',
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

  Widget _buildStatCard(String label, String count, Color color) {
    return Container(
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
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Quadrado de cor
            Container(
              width: 48,
              height: 48,
              decoration: BoxDecoration(
                color: color,
                borderRadius: BorderRadius.circular(6),
              ),
            ),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  label,
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppTheme.textSecondaryColor,
                    fontWeight: FontWeight.w500,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  count,
                  style: const TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textPrimaryColor,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildChamadoRecenteCard({
    required String tipo,
    required String titulo,
    required String localizacao,
    required String data,
    required String status,
    required Color statusColor,
  }) {
    return Container(
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
          // Header com tipo e localização
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      tipo,
                      style: const TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.w600,
                        color: AppTheme.textSecondaryColor,
                      ),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      titulo,
                      style: const TextStyle(
                        fontSize: 13,
                        fontWeight: FontWeight.w600,
                        color: AppTheme.textPrimaryColor,
                      ),
                    ),
                  ],
                ),
              ),
              Text(
                localizacao,
                style: const TextStyle(
                  fontSize: 12,
                  fontWeight: FontWeight.w500,
                  color: AppTheme.textSecondaryColor,
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),

          // Footer com data e status
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                data,
                style: const TextStyle(
                  fontSize: 11,
                  color: AppTheme.textSecondaryColor,
                ),
              ),
              Text(
                status,
                style: TextStyle(
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                  color: statusColor,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
