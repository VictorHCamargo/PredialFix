import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import 'app_drawer.dart';

class SupportScreen extends StatefulWidget {
  const SupportScreen({super.key});

  @override
  State<SupportScreen> createState() => _SupportScreenState();
}

class _SupportScreenState extends State<SupportScreen> {
  final _subjectController = TextEditingController();
  final _messageController = TextEditingController();

  @override
  void dispose() {
    _subjectController.dispose();
    _messageController.dispose();
    super.dispose();
  }

  void _submitSupport() {
    if (_subjectController.text.isEmpty || _messageController.text.isEmpty) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text('Preencha todos os campos')));
      return;
    }

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Mensagem enviada! Entraremos em contato em breve.'),
      ),
    );

    _subjectController.clear();
    _messageController.clear();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: const AppDrawer(currentPage: MenuPage.support),
      appBar: AppBar(title: const Text('Suporte')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(AppTheme.paddingLarge),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Card de contatos
            Container(
              decoration: AppTheme.getCardDecoration(),
              padding: const EdgeInsets.all(AppTheme.paddingLarge),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Contato e Suporte',
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: AppTheme.textPrimaryColor,
                    ),
                  ),
                  const SizedBox(height: 16),
                  _buildContactItem(
                    icon: Icons.location_on,
                    title: 'Endereço',
                    value: 'Av. Paulista, 1313, São Paulo/SP\nCEP 01311-930',
                  ),
                  const SizedBox(height: 12),
                  _buildContactItem(
                    icon: Icons.phone,
                    title: 'Telefone',
                    value: '(11) 3222-0039',
                  ),
                  const SizedBox(height: 12),
                  _buildContactItem(
                    icon: Icons.message,
                    title: 'WhatsApp',
                    value: '0800-055-1000',
                  ),
                  const SizedBox(height: 12),
                  _buildContactItem(
                    icon: Icons.email,
                    title: 'Email',
                    value: 'suporte@senai.br',
                  ),
                ],
              ),
            ),
            const SizedBox(height: AppTheme.paddingLarge),

            // Horário de atendimento
            Container(
              decoration: AppTheme.getCardDecoration(),
              padding: const EdgeInsets.all(AppTheme.paddingLarge),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Horário de Atendimento',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: AppTheme.textPrimaryColor,
                    ),
                  ),
                  const SizedBox(height: 12),
                  const Text(
                    'Segunda a Sexta: 08:00 às 18:00\nSábado: 08:00 às 12:00\nDomingo e Feriados: Fechado',
                    style: TextStyle(
                      fontSize: 14,
                      color: AppTheme.textSecondaryColor,
                      height: 1.8,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: AppTheme.paddingLarge),

            // Formulário de suporte
            const Text(
              'Enviar Mensagem',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: AppTheme.textPrimaryColor,
              ),
            ),
            const SizedBox(height: 12),

            Container(
              decoration: AppTheme.getCardDecoration(),
              padding: const EdgeInsets.all(AppTheme.paddingLarge),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  TextField(
                    controller: _subjectController,
                    decoration: AppTheme.getFieldDecoration('Assunto'),
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _messageController,
                    maxLines: 5,
                    decoration: InputDecoration(
                      labelText: 'Mensagem',
                      filled: true,
                      fillColor: AppTheme.inputBackgroundColor,
                      contentPadding: const EdgeInsets.symmetric(
                        horizontal: 16,
                        vertical: 14,
                      ),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(
                          AppTheme.radiusMedium,
                        ),
                        borderSide: BorderSide.none,
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),
                  SizedBox(
                    width: double.infinity,
                    height: 48,
                    child: ElevatedButton(
                      onPressed: _submitSupport,
                      child: const Text('Enviar Mensagem'),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: AppTheme.paddingLarge),

            // FAQ
            const Text(
              'Perguntas Frequentes',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: AppTheme.textPrimaryColor,
              ),
            ),
            const SizedBox(height: 12),
            _buildFaqItem(
              question: 'Como abrir um novo chamado?',
              answer:
                  'Clique em "Novo Chamado" na página inicial e preencha o formulário.',
            ),
            const SizedBox(height: 8),
            _buildFaqItem(
              question: 'Qual é o tempo de resposta?',
              answer:
                  'Chamados críticos em até 2 horas, alta em 4 horas e outros em 24 horas.',
            ),
            const SizedBox(height: 8),
            _buildFaqItem(
              question: 'Posso cancelar um chamado?',
              answer:
                  'Sim, você pode cancelar um chamado que ainda não foi iniciado.',
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildContactItem({
    required IconData icon,
    required String title,
    required String value,
  }) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(icon, size: 24, color: AppTheme.primaryColor),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title,
                style: const TextStyle(
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                  color: AppTheme.textSecondaryColor,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                value,
                style: const TextStyle(
                  fontSize: 13,
                  color: AppTheme.textPrimaryColor,
                  height: 1.5,
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildFaqItem({required String question, required String answer}) {
    return Container(
      decoration: AppTheme.getCardDecoration(),
      padding: const EdgeInsets.all(AppTheme.paddingMedium),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            question,
            style: const TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: AppTheme.textPrimaryColor,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            answer,
            style: const TextStyle(
              fontSize: 12,
              color: AppTheme.textSecondaryColor,
              height: 1.6,
            ),
          ),
        ],
      ),
    );
  }
}
