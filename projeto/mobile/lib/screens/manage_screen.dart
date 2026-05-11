import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import 'app_drawer.dart';

class ManageScreen extends StatelessWidget {
  const ManageScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: const AppDrawer(currentPage: MenuPage.manage),
      appBar: AppBar(
        title: const Text('Meus Chamados'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(AppTheme.paddingLarge),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Chamados Abertos',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppTheme.textPrimaryColor,
              ),
            ),
            const SizedBox(height: 12),

            // Exemplo de chamado
            _buildChamadoCard(
              numero: '#001',
              tipo: 'Reparo Elétrico',
              descricao: 'Luzes da sala 102 não funcionam',
              prioridade: 'Alta',
              status: 'Em Andamento',
              data: '12/05/2026',
            ),
            const SizedBox(height: 12),
            _buildChamadoCard(
              numero: '#002',
              tipo: 'Vazamento',
              descricao: 'Vazamento de água no banheiro',
              prioridade: 'Crítica',
              status: 'Aguardando Atendimento',
              data: '11/05/2026',
            ),
            const SizedBox(height: 12),
            _buildChamadoCard(
              numero: '#003',
              tipo: 'Manutenção',
              descricao: 'Revisão do sistema de ar condicionado',
              prioridade: 'Média',
              status: 'Concluído',
              data: '10/05/2026',
            ),
            const SizedBox(height: AppTheme.paddingLarge),

            // Botão para novo chamado
            SizedBox(
              width: double.infinity,
              height: 48,
              child: ElevatedButton.icon(
                onPressed: () => Navigator.pushNamed(context, '/request'),
                icon: const Icon(Icons.add),
                label: const Text('Abrir Novo Chamado'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildChamadoCard({
    required String numero,
    required String tipo,
    required String descricao,
    required String prioridade,
    required String status,
    required String data,
  }) {
    Color priorityColor;
    if (prioridade == 'Crítica') {
      priorityColor = AppTheme.errorColor;
    } else if (prioridade == 'Alta') {
      priorityColor = AppTheme.warningColor;
    } else if (prioridade == 'Média') {
      priorityColor = AppTheme.infoColor;
    } else {
      priorityColor = AppTheme.successColor;
    }

    return Container(
      decoration: AppTheme.getCardDecoration(),
      padding: const EdgeInsets.all(AppTheme.paddingMedium),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                numero,
                style: const TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.bold,
                  color: AppTheme.primaryColor,
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: priorityColor.withOpacity(0.2),
                  borderRadius: BorderRadius.circular(4),
                ),
                child: Text(
                  prioridade,
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                    color: priorityColor,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Text(
            tipo,
            style: const TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.w600,
              color: AppTheme.textPrimaryColor,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            descricao,
            style: const TextStyle(
              fontSize: 12,
              color: AppTheme.textSecondaryColor,
              height: 1.5,
            ),
          ),
          const SizedBox(height: 12),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: AppTheme.primaryColor.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(4),
                ),
                child: Text(
                  status,
                  style: const TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                    color: AppTheme.primaryColor,
                  ),
                ),
              ),
              Text(
                data,
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
}
