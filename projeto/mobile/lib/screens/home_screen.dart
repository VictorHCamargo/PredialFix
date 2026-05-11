import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import 'app_drawer.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: const AppDrawer(currentPage: MenuPage.home),
      appBar: AppBar(
        title: const Text('Página Inicial'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(AppTheme.paddingLarge),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Card de Boas-vindas
            Container(
              decoration: AppTheme.getCardDecoration(),
              padding: const EdgeInsets.all(AppTheme.paddingLarge),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Bem-vindo ao PredialFix!',
                    style: TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: AppTheme.textPrimaryColor,
                    ),
                  ),
                  const SizedBox(height: 12),
                  const Text(
                    'Sistema de gerenciamento de chamados prediais. Aqui você pode abrir novos chamados, acompanhar seu status e avaliar o atendimento.',
                    style: TextStyle(
                      fontSize: 14,
                      color: AppTheme.textSecondaryColor,
                      height: 1.6,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: AppTheme.paddingLarge),

            // Secção de Ações Rápidas
            const Text(
              'Ações Rápidas',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppTheme.textPrimaryColor,
              ),
            ),
            const SizedBox(height: 12),

            // Grid de Ações
            GridView.count(
              crossAxisCount: 2,
              crossAxisSpacing: 12,
              mainAxisSpacing: 12,
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              children: [
                _buildActionCard(
                  context,
                  icon: Icons.add_circle_outline,
                  title: 'Novo Chamado',
                  onTap: () => Navigator.pushNamed(context, '/request'),
                ),
                _buildActionCard(
                  context,
                  icon: Icons.assignment,
                  title: 'Meus Chamados',
                  onTap: () => Navigator.pushNamed(context, '/manage'),
                ),
                _buildActionCard(
                  context,
                  icon: Icons.star,
                  title: 'Avaliações',
                  onTap: () => Navigator.pushNamed(context, '/ratings'),
                ),
                _buildActionCard(
                  context,
                  icon: Icons.help_outline,
                  title: 'Suporte',
                  onTap: () => Navigator.pushNamed(context, '/support'),
                ),
              ],
            ),
            const SizedBox(height: AppTheme.paddingLarge),

            // Informações Úteis
            const Text(
              'Informações Úteis',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppTheme.textPrimaryColor,
              ),
            ),
            const SizedBox(height: 12),
            _buildInfoCard(
              icon: Icons.info_outline,
              title: 'Como usar o sistema?',
              description: 'Clique em "Novo Chamado" para abrir um novo pedido de manutenção.',
            ),
            const SizedBox(height: 8),
            _buildInfoCard(
              icon: Icons.schedule,
              title: 'Tempo de resposta',
              description: 'Chamados críticos são atendidos dentro de 2 horas.',
            ),
            const SizedBox(height: 8),
            _buildInfoCard(
              icon: Icons.phone,
              title: 'Contato SENAI',
              description: 'Tel: (11) 3222-0039 | WhatsApp: 0800-055-1000',
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildActionCard(
    BuildContext context, {
    required IconData icon,
    required String title,
    required VoidCallback onTap,
  }) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        decoration: AppTheme.getCardDecoration(),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              icon,
              size: 36,
              color: AppTheme.primaryColor,
            ),
            const SizedBox(height: 12),
            Text(
              title,
              textAlign: TextAlign.center,
              style: const TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.w600,
                color: AppTheme.textPrimaryColor,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoCard({
    required IconData icon,
    required String title,
    required String description,
  }) {
    return Container(
      decoration: AppTheme.getCardDecoration(),
      padding: const EdgeInsets.all(AppTheme.paddingMedium),
      child: Row(
        children: [
          Icon(
            icon,
            size: 28,
            color: AppTheme.primaryColor,
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 13,
                    fontWeight: FontWeight.w600,
                    color: AppTheme.textPrimaryColor,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  description,
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppTheme.textSecondaryColor,
                    height: 1.4,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
