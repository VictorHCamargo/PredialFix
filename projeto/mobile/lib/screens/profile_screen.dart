import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/chamado.dart';
import '../models/feedback.dart' as FeedbackModel;
import '../models/user.dart';
import '../services/auth_service.dart';
import '../services/chamado_service.dart';
import '../services/feedback_service.dart';
import '../theme/app_theme.dart';
import 'app_drawer.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  User? _user;
  List<Chamado> _recentChamados = [];
  List<FeedbackModel.Feedback> _recentFeedbacks = [];
  bool _isLoading = true;
  bool _loaded = false;
  String? _errorMessage;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_loaded) {
      _loaded = true;
      _loadProfile();
    }
  }

  Future<void> _loadProfile() async {
    final authService = context.read<AuthService>();
    final chamadoService = context.read<ChamadoService>();
    final feedbackService = context.read<FeedbackService>();

    try {
      final user = await authService.getCurrentUser();
      final chamados = await chamadoService.getChamados();
      var feedbacks = <FeedbackModel.Feedback>[];

      try {
        feedbacks = await feedbackService.getFeedbacks();
      } catch (_) {
        feedbacks = [];
      }

      if (!mounted) return;
      setState(() {
        _user = user;
        _recentChamados = chamados.take(5).toList();
        _recentFeedbacks = feedbacks.take(5).toList();
        _errorMessage = null;
        _isLoading = false;
      });
    } catch (_) {
      if (!mounted) return;
      setState(() {
        _errorMessage = 'Erro ao carregar perfil';
        _isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao carregar perfil')),
      );
    }
  }

  Future<void> _handleLogout() async {
    final authService = context.read<AuthService>();
    await authService.logout();

    if (!mounted) return;
    Navigator.of(context).pushReplacementNamed('/login');
  }

  Future<void> _showEditProfileDialog() async {
    final user = _user;
    if (user == null) return;

    final nomeController = TextEditingController(text: user.nome);
    final emailController = TextEditingController(text: user.email);
    final telefoneController = TextEditingController(text: user.telefone ?? '');
    final cpfController = TextEditingController(text: user.cpf ?? '');
    final screenContext = context;
    var isSaving = false;

    try {
      await showDialog(
        context: context,
        builder: (dialogContext) => StatefulBuilder(
          builder: (context, setDialogState) {
            return AlertDialog(
              title: const Text('Editar Perfil'),
              content: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    TextField(
                      controller: nomeController,
                      enabled: !isSaving,
                      decoration: const InputDecoration(labelText: 'Nome'),
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: emailController,
                      enabled: !isSaving,
                      keyboardType: TextInputType.emailAddress,
                      decoration: const InputDecoration(labelText: 'Email'),
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: telefoneController,
                      enabled: !isSaving,
                      keyboardType: TextInputType.phone,
                      decoration: const InputDecoration(labelText: 'Telefone'),
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: cpfController,
                      enabled: !isSaving,
                      decoration: const InputDecoration(labelText: 'CPF'),
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
                  onPressed: isSaving
                      ? null
                      : () async {
                          final navigator = Navigator.of(dialogContext);
                          final messenger = ScaffoldMessenger.of(screenContext);
                          final authService = screenContext.read<AuthService>();

                          setDialogState(() => isSaving = true);
                          final updated = await authService.updateProfile(
                            nome: nomeController.text.trim(),
                            email: emailController.text.trim(),
                            telefone: telefoneController.text.trim().isEmpty
                                ? null
                                : telefoneController.text.trim(),
                            cpf: cpfController.text.trim().isEmpty
                                ? null
                                : cpfController.text.trim(),
                          );

                          if (!mounted) return;

                          if (updated != null) {
                            setState(() => _user = updated);
                            navigator.pop();
                            messenger.showSnackBar(
                              const SnackBar(
                                content: Text('Perfil atualizado!'),
                                backgroundColor: Colors.green,
                              ),
                            );
                          } else {
                            setDialogState(() => isSaving = false);
                            messenger.showSnackBar(
                              const SnackBar(
                                content: Text('Erro ao atualizar perfil'),
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
      nomeController.dispose();
      emailController.dispose();
      telefoneController.dispose();
      cpfController.dispose();
    }
  }

  Future<void> _showPasswordDialog() async {
    final currentController = TextEditingController();
    final newController = TextEditingController();
    final confirmController = TextEditingController();
    final screenContext = context;
    var isSaving = false;

    try {
      await showDialog(
        context: context,
        builder: (dialogContext) => StatefulBuilder(
          builder: (context, setDialogState) {
            return AlertDialog(
              title: const Text('Alterar Senha'),
              content: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    TextField(
                      controller: currentController,
                      obscureText: true,
                      enabled: !isSaving,
                      decoration: const InputDecoration(labelText: 'Senha atual'),
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: newController,
                      obscureText: true,
                      enabled: !isSaving,
                      decoration: const InputDecoration(labelText: 'Nova senha'),
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: confirmController,
                      obscureText: true,
                      enabled: !isSaving,
                      decoration: const InputDecoration(
                        labelText: 'Confirmar nova senha',
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
                  onPressed: isSaving
                      ? null
                      : () async {
                          final navigator = Navigator.of(dialogContext);
                          final messenger = ScaffoldMessenger.of(screenContext);
                          final authService = screenContext.read<AuthService>();

                          setDialogState(() => isSaving = true);
                          final success = await authService.updatePassword(
                            currentPassword: currentController.text,
                            newPassword: newController.text,
                            confirmation: confirmController.text,
                          );

                          if (!mounted) return;

                          if (success) {
                            navigator.pop();
                            messenger.showSnackBar(
                              const SnackBar(
                                content: Text('Senha atualizada!'),
                                backgroundColor: Colors.green,
                              ),
                            );
                          } else {
                            setDialogState(() => isSaving = false);
                            messenger.showSnackBar(
                              const SnackBar(
                                content: Text('Erro ao atualizar senha'),
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
      currentController.dispose();
      newController.dispose();
      confirmController.dispose();
    }
  }

  Future<void> _showDeleteAccountDialog() async {
    final passwordController = TextEditingController();
    final screenContext = context;
    var isSaving = false;

    try {
      await showDialog(
        context: context,
        builder: (dialogContext) => StatefulBuilder(
          builder: (context, setDialogState) {
            return AlertDialog(
              title: const Text('Excluir Conta'),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Text(
                    'Esta ação remove sua conta local e limpa a sessão.',
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: passwordController,
                    obscureText: true,
                    enabled: !isSaving,
                    decoration: const InputDecoration(labelText: 'Senha'),
                  ),
                ],
              ),
              actions: [
                TextButton(
                  onPressed: isSaving
                      ? null
                      : () => Navigator.of(dialogContext).pop(),
                  child: const Text('Cancelar'),
                ),
                ElevatedButton(
                  style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
                  onPressed: isSaving
                      ? null
                      : () async {
                          final navigator = Navigator.of(dialogContext);
                          final messenger = ScaffoldMessenger.of(screenContext);
                          final authService = screenContext.read<AuthService>();

                          setDialogState(() => isSaving = true);
                          final success = await authService.deleteAccount(
                            passwordController.text,
                          );

                          if (!mounted) return;

                          if (success) {
                            navigator.pop();
                            Navigator.of(
                              screenContext,
                            ).pushReplacementNamed('/login');
                          } else {
                            setDialogState(() => isSaving = false);
                            messenger.showSnackBar(
                              const SnackBar(
                                content: Text('Senha incorreta'),
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
                      : const Text('Excluir'),
                ),
              ],
            );
          },
        ),
      );
    } finally {
      passwordController.dispose();
    }
  }

  void _showLogoutDialog() {
    showDialog(
      context: context,
      builder: (dialogContext) => AlertDialog(
        title: const Text('Sair'),
        content: const Text('Tem certeza que deseja sair?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(dialogContext),
            child: const Text('Cancelar'),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(dialogContext);
              _handleLogout();
            },
            child: const Text('Sair'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      drawer: const AppDrawer(),
      appBar: AppBar(
        title: const Text('Perfil'),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _errorMessage != null
          ? _buildErrorState()
          : _user == null
          ? const Center(child: Text('Nenhum usuário carregado'))
          : RefreshIndicator(
              onRefresh: _loadProfile,
              child: ListView(
                children: [
                  _buildHeader(),
                  Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      children: [
                        _buildInfoCard(
                          icon: Icons.person,
                          label: 'Nome',
                          value: _user!.nome,
                        ),
                        const SizedBox(height: 12),
                        _buildInfoCard(
                          icon: Icons.email,
                          label: 'Email',
                          value: _user!.email,
                        ),
                        const SizedBox(height: 12),
                        _buildInfoCard(
                          icon: Icons.phone,
                          label: 'Telefone',
                          value: _user!.telefone ?? 'Não informado',
                        ),
                        const SizedBox(height: 12),
                        _buildInfoCard(
                          icon: Icons.badge,
                          label: 'Papel',
                          value: _roleLabel(_user!.role),
                        ),
                        const SizedBox(height: 24),
                        _buildActions(),
                        const SizedBox(height: 24),
                        _buildRecentChamados(),
                        const SizedBox(height: 24),
                        _buildRecentFeedbacks(),
                      ],
                    ),
                  ),
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
              onPressed: _loadProfile,
              child: const Text('Tentar novamente'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(vertical: 32),
      decoration: const BoxDecoration(
        color: AppTheme.primaryColor,
        borderRadius: BorderRadius.only(
          bottomLeft: Radius.circular(16),
          bottomRight: Radius.circular(16),
        ),
      ),
      child: Column(
        children: [
          CircleAvatar(
            radius: 50,
            backgroundColor: Colors.white,
            child: Text(
              (_user!.nome[0]).toUpperCase(),
              style: const TextStyle(
                fontSize: 32,
                fontWeight: FontWeight.bold,
                color: AppTheme.primaryColor,
              ),
            ),
          ),
          const SizedBox(height: 16),
          Text(
            _user!.nome,
            style: const TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: Colors.white,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            _user!.email,
            style: const TextStyle(fontSize: 14, color: Colors.white70),
          ),
        ],
      ),
    );
  }

  Widget _buildActions() {
    return Column(
      children: [
        Row(
          children: [
            Expanded(
              child: OutlinedButton.icon(
                onPressed: _showEditProfileDialog,
                icon: const Icon(Icons.edit),
                label: const Text('Editar'),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: OutlinedButton.icon(
                onPressed: _showPasswordDialog,
                icon: const Icon(Icons.lock),
                label: const Text('Senha'),
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(
              child: ElevatedButton.icon(
                style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
                onPressed: _showLogoutDialog,
                icon: const Icon(Icons.logout),
                label: const Text('Sair'),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: OutlinedButton.icon(
                style: OutlinedButton.styleFrom(foregroundColor: Colors.red),
                onPressed: _showDeleteAccountDialog,
                icon: const Icon(Icons.delete_outline),
                label: const Text('Excluir'),
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildRecentChamados() {
    return _buildSection(
      title: 'Chamados Recentes',
      emptyText: 'Nenhum chamado recente',
      children: _recentChamados
          .map(
            (chamado) => ListTile(
              contentPadding: EdgeInsets.zero,
              leading: const Icon(Icons.assignment_outlined),
              title: Text(chamado.descricao),
              subtitle: Text(chamado.displayStatus),
            ),
          )
          .toList(),
    );
  }

  Widget _buildRecentFeedbacks() {
    return _buildSection(
      title: 'Avaliações Recentes',
      emptyText: 'Nenhuma avaliação registrada',
      children: _recentFeedbacks
          .map(
            (feedback) => ListTile(
              contentPadding: EdgeInsets.zero,
              leading: const Icon(Icons.star, color: Colors.amber),
              title: Text('${feedback.classificacao}/5 estrelas'),
              subtitle: Text(feedback.comentario ?? 'Sem comentário'),
            ),
          )
          .toList(),
    );
  }

  Widget _buildSection({
    required String title,
    required String emptyText,
    required List<Widget> children,
  }) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: AppTheme.getCardDecoration(borderRadius: 8),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          if (children.isEmpty)
            Text(
              emptyText,
              style: const TextStyle(color: AppTheme.textSecondaryColor),
            )
          else
            ...children,
        ],
      ),
    );
  }

  Widget _buildInfoCard({
    required IconData icon,
    required String label,
    required String value,
  }) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.grey[100],
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        children: [
          Icon(icon, color: AppTheme.primaryColor),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  label,
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppTheme.textSecondaryColor,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  value,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
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

  String _roleLabel(String role) {
    switch (role) {
      case 'administrador':
        return 'Administrador';
      case 'gerente_manutencao':
        return 'Gerente de Manutenção';
      case 'tecnico_manutencao':
        return 'Técnico de Manutenção';
      case 'professor':
        return 'Professor';
      case 'aluno':
        return 'Aluno';
      default:
        return role;
    }
  }
}
