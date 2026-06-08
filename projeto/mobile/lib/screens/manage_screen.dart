import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../models/chamado.dart';
import '../models/equipamento.dart';
import '../models/historico_status_chamado.dart';
import '../models/local.dart';
import '../models/tipo_problema.dart';
import '../models/user.dart';
import '../services/auth_service.dart';
import '../services/chamado_service.dart';
import '../services/equipamento_service.dart';
import '../services/reference_service.dart';
import '../theme/app_theme.dart';
import 'app_drawer.dart';

class ManageScreen extends StatefulWidget {
  const ManageScreen({super.key});

  @override
  State<ManageScreen> createState() => _ManageScreenState();
}

class _ManageScreenState extends State<ManageScreen> {
  late ChamadoService _chamadoService;
  late AuthService _authService;
  late ReferenceService _referenceService;
  late EquipamentoService _equipamentoService;

  List<Chamado> _chamados = [];
  List<Local> _locais = [];
  List<TipoProblema> _tipos = [];
  List<Equipamento> _equipamentos = [];
  User? _currentUser;
  bool _isLoading = true;
  bool _loaded = false;
  String? _selectedStatus;
  String? _errorMessage;

  bool get _canManageStatus {
    final role = _currentUser?.role;
    return role == 'administrador' ||
        role == 'gerente_manutencao' ||
        role == 'tecnico_manutencao';
  }

  bool _canEditChamado(Chamado chamado) {
    final role = _currentUser?.role;
    final isOwner = _currentUser?.id == chamado.idUsuario;
    final isOpen = _normalizeStatus(chamado.status) == 'pendente';
    if (role == 'administrador') return true;
    return role != 'aluno' && isOwner && isOpen;
  }

  bool _canDeleteChamado(Chamado chamado) {
    final role = _currentUser?.role;
    final isOwner = _currentUser?.id == chamado.idUsuario;
    final isOpen = _normalizeStatus(chamado.status) == 'pendente';
    return role == 'administrador' || (role == 'professor' && isOwner && isOpen);
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_loaded) {
      _loaded = true;
      _chamadoService = context.read<ChamadoService>();
      _authService = context.read<AuthService>();
      _referenceService = context.read<ReferenceService>();
      _equipamentoService = context.read<EquipamentoService>();
      _loadUser();
      _loadReferences();
      _loadChamados();
    }
  }

  Future<void> _loadReferences() async {
    try {
      final locais = await _referenceService.getLocais();
      final tipos = await _referenceService.getTiposProblema();
      var equipamentos = <Equipamento>[];
      try {
        equipamentos = await _equipamentoService.getEquipamentos();
      } catch (_) {}
      if (!mounted) return;
      setState(() {
        _locais = locais;
        _tipos = tipos;
        _equipamentos = equipamentos;
      });
    } catch (_) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao carregar referências')),
      );
    }
  }

  Future<void> _loadUser() async {
    try {
      final user = await _authService.getCurrentUser();
      if (!mounted) return;
      setState(() => _currentUser = user);
    } catch (_) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao carregar usuário')),
      );
    }
  }

  Future<void> _loadChamados() async {
    if (!mounted) return;
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
        .where((c) => _matchesStatus(c.status, _selectedStatus!))
        .toList();
  }

  int _countStatus(String status) {
    return _chamados.where((c) => _matchesStatus(c.status, status)).length;
  }

  bool _matchesStatus(String current, String expected) {
    final normalizedCurrent = _normalizeStatus(current);
    final normalizedExpected = _normalizeStatus(expected);
    return normalizedCurrent == normalizedExpected;
  }

  String _normalizeStatus(String status) {
    switch (status.toLowerCase()) {
      case 'aberto':
      case 'pendente':
        return 'pendente';
      case 'em andamento':
      case 'em_andamento':
        return 'em_andamento';
      case 'concluido':
      case 'concluído':
        return 'concluido';
      case 'cancelado':
        return 'cancelado';
      default:
        return status.toLowerCase();
    }
  }

  String _formatDate(DateTime date) {
    return DateFormat('dd/MM/yyyy HH:mm').format(date);
  }

  String _optionText(String? value) {
    switch (value) {
      case 'eletrica':
        return 'Elétrica';
      case 'hidraulica':
        return 'Hidráulica';
      case 'mecanica':
        return 'Mecânica';
      case 'media':
        return 'Média';
      case 'preventiva':
        return 'Preventiva';
      case 'corretiva':
        return 'Corretiva';
      case 'melhoria':
        return 'Melhoria';
      case 'simples':
        return 'Simples';
      case 'complexa':
        return 'Complexa';
      case 'civil':
        return 'Civil';
      case 'interno':
        return 'Interno';
      case 'externo':
        return 'Externo';
      case null:
        return 'Não informado';
      default:
        return value;
    }
  }

  String _priorityText(String? prioridade) {
    if (prioridade == null) return 'Não definida';
    switch (_normalizePriority(prioridade)) {
      case 'alta':
        return 'Alta';
      case 'media':
        return 'Média';
      case 'baixa':
        return 'Baixa';
      default:
        return 'Não definida';
    }
  }

  String _normalizePriority(String prioridade) {
    switch (prioridade.toLowerCase()) {
      case 'alta':
        return 'alta';
      case 'media':
      case 'média':
        return 'media';
      case 'baixa':
        return 'baixa';
      default:
        return 'media';
    }
  }

  Future<void> _showStatusDialog(Chamado chamado) async {
    final screenContext = context;
    final descricaoController = TextEditingController();
    var selectedStatus = _normalizeStatus(chamado.status);
    var selectedPrioridade = _normalizePriority(chamado.prioridade ?? 'media');
    var isSaving = false;

    try {
      await showDialog<void>(
        context: context,
        builder: (dialogContext) {
          return StatefulBuilder(
            builder: (BuildContext buildContext, StateSetter setDialogState) {
              return WillPopScope(
                onWillPop: () async => !isSaving,
                child: AlertDialog(
                  title: const Text('Alterar status'),
                  content: SingleChildScrollView(
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        DropdownButtonFormField<String>(
                          value: selectedStatus,
                          decoration:
                              const InputDecoration(labelText: 'Status'),
                          items: const [
                            DropdownMenuItem(
                              value: 'pendente',
                              child: Text('Pendente'),
                            ),
                            DropdownMenuItem(
                              value: 'em_andamento',
                              child: Text('Em andamento'),
                            ),
                            DropdownMenuItem(
                              value: 'concluido',
                              child: Text('Concluído'),
                            ),
                            DropdownMenuItem(
                              value: 'cancelado',
                              child: Text('Cancelado'),
                            ),
                          ],
                          onChanged: isSaving
                              ? null
                              : (value) {
                                  setDialogState(() {
                                    selectedStatus = value ?? selectedStatus;
                                  });
                                },
                        ),
                        const SizedBox(height: 12),
                        DropdownButtonFormField<String>(
                          value: selectedPrioridade,
                          decoration: const InputDecoration(
                            labelText: 'Prioridade',
                          ),
                          items: const [
                            DropdownMenuItem(
                              value: 'baixa',
                              child: Text('Baixa'),
                            ),
                            DropdownMenuItem(
                              value: 'media',
                              child: Text('Média'),
                            ),
                            DropdownMenuItem(value: 'alta', child: Text('Alta')),
                          ],
                          onChanged: isSaving
                              ? null
                              : (value) {
                                  setDialogState(() {
                                    selectedPrioridade =
                                        value ?? selectedPrioridade;
                                  });
                                },
                        ),
                        const SizedBox(height: 12),
                        TextField(
                          controller: descricaoController,
                          enabled: !isSaving,
                          maxLines: 3,
                          decoration: const InputDecoration(
                            labelText: 'Observação',
                          ),
                        ),
                      ],
                    ),
                  ),
                  actions: [
                    TextButton(
                      onPressed: isSaving
                          ? null
                          : () {
                              Navigator.of(dialogContext).pop();
                            },
                      child: const Text('Cancelar'),
                    ),
                    ElevatedButton(
                      onPressed: isSaving
                          ? null
                          : () async {
                              final navigator = Navigator.of(dialogContext);
                              final messenger =
                                  ScaffoldMessenger.of(screenContext);
                              setDialogState(() => isSaving = true);

                              final updated =
                                  await _chamadoService.updateStatus(
                                chamado.id,
                                status: selectedStatus,
                                prioridade: selectedPrioridade,
                                descricao: descricaoController.text,
                              );

                              if (!mounted) {
                                return;
                              }

                              navigator.pop();

                              if (updated != null) {
                                await _loadChamados();
                                messenger.showSnackBar(
                                  const SnackBar(
                                    content: Text('Status atualizado!'),
                                    backgroundColor: Colors.green,
                                  ),
                                );
                              } else {
                                messenger.showSnackBar(
                                  const SnackBar(
                                    content: Text('Erro ao atualizar status'),
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
                ),
              );
            },
          );
        },
      );
    } finally {
      descricaoController.dispose();
    }
  }

  Future<void> _showEditChamadoDialog(Chamado chamado) async {
    final screenContext = context;
    if (_locais.isEmpty || _tipos.isEmpty) {
      await _loadReferences();
      if (!screenContext.mounted) return;
    }

    if (_locais.isEmpty || _tipos.isEmpty) {
      if (!screenContext.mounted) return;
      ScaffoldMessenger.of(screenContext).showSnackBar(
        const SnackBar(content: Text('Não foi possível carregar o formulário')),
      );
      return;
    }

    final descricaoController = TextEditingController(text: chamado.descricao);
    var selectedLocalId = chamado.idLocal;
    var selectedTipoId = chamado.idTipo;
    var selectedEquipamentoId = chamado.idEquipamento;
    var selectedTipoChamado = chamado.tipoChamado ?? 'interno';
    if (selectedEquipamentoId != null &&
        !_equipamentos.any((item) => item.id == selectedEquipamentoId)) {
      selectedEquipamentoId = null;
    }
    var isSaving = false;

    try {
      await showDialog(
        context: screenContext,
        builder: (dialogContext) => StatefulBuilder(
          builder: (context, setDialogState) {
            return AlertDialog(
              title: const Text('Editar chamado'),
              content: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    DropdownButtonFormField<int>(
                      value: selectedLocalId,
                      decoration: const InputDecoration(labelText: 'Local'),
                      items: _locais
                          .map(
                            (local) => DropdownMenuItem(
                              value: local.id,
                              child: Text(local.nome),
                            ),
                          )
                          .toList(),
                      onChanged: isSaving
                          ? null
                          : (value) {
                              setDialogState(() {
                                selectedLocalId = value ?? selectedLocalId;
                              });
                            },
                    ),
                    const SizedBox(height: 12),
                    DropdownButtonFormField<int>(
                      value: selectedTipoId,
                      decoration: const InputDecoration(
                        labelText: 'Tipo de problema',
                      ),
                      items: _tipos
                          .map(
                            (tipo) => DropdownMenuItem(
                              value: tipo.id,
                              child: Text(tipo.nome),
                            ),
                          )
                          .toList(),
                      onChanged: isSaving
                          ? null
                          : (value) {
                              setDialogState(() {
                                selectedTipoId = value ?? selectedTipoId;
                              });
                            },
                    ),
                    const SizedBox(height: 12),
                    DropdownButtonFormField<int?>(
                      value: selectedEquipamentoId,
                      decoration: const InputDecoration(
                        labelText: 'Equipamento',
                      ),
                      items: [
                        const DropdownMenuItem<int?>(
                          value: null,
                          child: Text('Sem equipamento vinculado'),
                        ),
                        ..._equipamentos.map(
                          (equipamento) => DropdownMenuItem<int?>(
                            value: equipamento.id,
                            child: Text(equipamento.toString()),
                          ),
                        ),
                      ],
                      onChanged: isSaving
                          ? null
                          : (value) {
                              setDialogState(() {
                                selectedEquipamentoId = value;
                              });
                            },
                    ),
                    const SizedBox(height: 12),
                    DropdownButtonFormField<String>(
                      value: selectedTipoChamado,
                      decoration: const InputDecoration(
                        labelText: 'Tipo de chamado',
                      ),
                      items: const [
                        DropdownMenuItem(
                          value: 'interno',
                          child: Text('Interno'),
                        ),
                        DropdownMenuItem(
                          value: 'externo',
                          child: Text('Externo'),
                        ),
                      ],
                      onChanged: isSaving
                          ? null
                          : (value) {
                              setDialogState(() {
                                selectedTipoChamado =
                                    value ?? selectedTipoChamado;
                              });
                            },
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: descricaoController,
                      enabled: !isSaving,
                      maxLines: 4,
                      decoration: const InputDecoration(labelText: 'Descrição'),
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
                          setDialogState(() => isSaving = true);

                          final updated = await _chamadoService.updateChamado(
                            chamado.id,
                            descricao: descricaoController.text.trim(),
                            idLocal: selectedLocalId,
                            idTipo: selectedTipoId,
                            idEquipamento: selectedEquipamentoId,
                            tipoChamado: selectedTipoChamado,
                          );

                          if (!mounted) return;

                          if (updated != null) {
                            navigator.pop();
                            await _loadChamados();
                            messenger.showSnackBar(
                              const SnackBar(
                                content: Text('Chamado atualizado!'),
                                backgroundColor: Colors.green,
                              ),
                            );
                          } else {
                            setDialogState(() => isSaving = false);
                            messenger.showSnackBar(
                              const SnackBar(
                                content: Text('Erro ao atualizar chamado'),
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
      descricaoController.dispose();
    }
  }

  Future<void> _confirmDeleteChamado(Chamado chamado) async {
    final screenContext = context;

    await showDialog(
      context: context,
      builder: (dialogContext) => AlertDialog(
        title: const Text('Excluir chamado'),
        content: const Text('Tem certeza que deseja excluir este chamado?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(dialogContext).pop(),
            child: const Text('Cancelar'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            onPressed: () async {
              final navigator = Navigator.of(dialogContext);
              final messenger = ScaffoldMessenger.of(screenContext);
              final success = await _chamadoService.deleteChamado(chamado.id);

              if (!mounted) return;
              navigator.pop();

              if (success) {
                await _loadChamados();
                messenger.showSnackBar(
                  const SnackBar(
                    content: Text('Chamado excluído!'),
                    backgroundColor: Colors.green,
                  ),
                );
              } else {
                messenger.showSnackBar(
                  const SnackBar(content: Text('Erro ao excluir chamado')),
                );
              }
            },
            child: const Text('Excluir'),
          ),
        ],
      ),
    );
  }

  void _showChamadoDetails(Chamado chamado) {
    final historicoFuture = _chamadoService.getHistorico(chamado.id);

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (sheetContext) {
        return SafeArea(
          child: SizedBox(
            height: MediaQuery.of(sheetContext).size.height * 0.82,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Padding(
                  padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
                  child: Row(
                    children: [
                      Expanded(
                        child: Text(
                          'Chamado #${chamado.id}',
                          style: const TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                      if (_canManageStatus)
                        IconButton(
                          onPressed: () {
                            Navigator.of(sheetContext).pop();
                            _showStatusDialog(chamado);
                          },
                          icon: const Icon(Icons.edit_note),
                          tooltip: 'Alterar status',
                        ),
                      if (_canEditChamado(chamado))
                        IconButton(
                          onPressed: () {
                            Navigator.of(sheetContext).pop();
                            _showEditChamadoDialog(chamado);
                          },
                          icon: const Icon(Icons.edit_outlined),
                          tooltip: 'Editar chamado',
                        ),
                      if (_canDeleteChamado(chamado))
                        IconButton(
                          onPressed: () {
                            Navigator.of(sheetContext).pop();
                            _confirmDeleteChamado(chamado);
                          },
                          icon: const Icon(Icons.delete_outline),
                          color: Colors.red,
                          tooltip: 'Excluir chamado',
                        ),
                      IconButton(
                        onPressed: () => Navigator.of(sheetContext).pop(),
                        icon: const Icon(Icons.close),
                        tooltip: 'Fechar',
                      ),
                    ],
                  ),
                ),
                const Divider(height: 1),
                Expanded(
                  child: ListView(
                    padding: const EdgeInsets.all(16),
                    children: [
                      _buildDetailRow('Descrição', chamado.descricao),
                      _buildDetailRow(
                        'Status',
                        chamado.displayStatus,
                        color: _getStatusColor(chamado.status),
                      ),
                      _buildDetailRow(
                        'Prioridade',
                        _priorityText(chamado.prioridade),
                        color: _getPriorityColor(chamado.prioridade),
                      ),
                      _buildDetailRow(
                        'Tipo',
                        chamado.tipoProblema?.nome ?? 'Não informado',
                      ),
                      _buildDetailRow(
                        'Local',
                        chamado.local?.nome ?? 'Não informado',
                      ),
                      if (chamado.idEquipamento != null)
                        _buildDetailRow(
                          'Equipamento',
                          chamado.equipamento?.toString() ??
                              'ID ${chamado.idEquipamento}',
                        ),
                      _buildDetailRow(
                        'Tipo de chamado',
                        _optionText(chamado.tipoChamado ?? 'interno'),
                      ),
                      if (chamado.secaoTecnica != null)
                        _buildDetailRow(
                          'Seção técnica',
                          _optionText(chamado.secaoTecnica),
                        ),
                      if (chamado.complexidade != null)
                        _buildDetailRow(
                          'Complexidade',
                          _optionText(chamado.complexidade),
                        ),
                      if (chamado.tipoTrabalho != null)
                        _buildDetailRow(
                          'Tipo de trabalho',
                          _optionText(chamado.tipoTrabalho),
                        ),
                      _buildDetailRow(
                        'Solicitante',
                        chamado.usuario?.nome ?? 'Não informado',
                      ),
                      _buildDetailRow(
                        'Aberto em',
                        _formatDate(chamado.dataAbertura),
                      ),
                      if (chamado.dataFechamento != null)
                        _buildDetailRow(
                          'Fechado em',
                          _formatDate(chamado.dataFechamento!),
                        ),
                      const SizedBox(height: 20),
                      const Text(
                        'Histórico',
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 8),
                      FutureBuilder<List<HistoricoStatusChamado>>(
                        future: historicoFuture,
                        builder: (context, snapshot) {
                          if (snapshot.connectionState ==
                              ConnectionState.waiting) {
                            return const Padding(
                              padding: EdgeInsets.all(16),
                              child: Center(child: CircularProgressIndicator()),
                            );
                          }

                          final historico = snapshot.data ?? [];
                          if (historico.isEmpty) {
                            return const Text(
                              'Nenhuma alteração registrada.',
                              style: TextStyle(
                                color: AppTheme.textSecondaryColor,
                              ),
                            );
                          }

                          return Column(
                            children: historico
                                .map((item) => _buildHistoryItem(item))
                                .toList(),
                          );
                        },
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildDetailRow(String label, String value, {Color? color}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
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
          const SizedBox(height: 2),
          Text(
            value,
            style: TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.w600,
              color: color ?? AppTheme.textPrimaryColor,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHistoryItem(HistoricoStatusChamado item) {
    return Container(
      width: double.infinity,
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.grey.withValues(alpha: 0.08),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            '${item.statusAnterior} -> ${item.statusNovo}',
            style: const TextStyle(fontWeight: FontWeight.w600),
          ),
          const SizedBox(height: 4),
          Text(item.descricao, style: const TextStyle(fontSize: 13)),
          const SizedBox(height: 4),
          Text(
            DateFormat('dd/MM/yyyy HH:mm').format(item.dataMudanca),
            style: const TextStyle(
              fontSize: 12,
              color: AppTheme.textSecondaryColor,
            ),
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
        title: const Text('Meus Chamados'),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _errorMessage != null
              ? Center(
                  child: Padding(
                    padding: const EdgeInsets.all(24),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Text(_errorMessage!, textAlign: TextAlign.center),
                        const SizedBox(height: 12),
                        ElevatedButton(
                          onPressed: _loadChamados,
                          child: const Text('Tentar novamente'),
                        ),
                      ],
                    ),
                  ),
                )
              : RefreshIndicator(
                  onRefresh: _loadChamados,
                  child: ListView(
                    padding: const EdgeInsets.only(bottom: 24),
                    children: [
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
                                value: '${_countStatus('em_andamento')}',
                                icon: Icons.hourglass_bottom,
                                color: Colors.orange,
                              ),
                              const SizedBox(width: 12),
                              _buildStatCard(
                                title: 'Concluídos',
                                value: '${_countStatus('concluido')}',
                                icon: Icons.check_circle,
                                color: Colors.green,
                              ),
                              const SizedBox(width: 12),
                              _buildStatCard(
                                title: 'Pendentes',
                                value: '${_countStatus('pendente')}',
                                icon: Icons.pending_actions,
                                color: Colors.red,
                              ),
                            ],
                          ),
                        ),
                      ),
                      Padding(
                        padding: const EdgeInsets.fromLTRB(16, 8, 16, 16),
                        child: Container(
                          decoration: AppTheme.getCardDecoration(
                            borderRadius: 8,
                          ),
                          padding: const EdgeInsets.all(12),
                          child: DropdownButtonFormField<String>(
                            value: _selectedStatus,
                            decoration: const InputDecoration(
                              labelText: 'Filtrar por status',
                            ),
                            items: const [
                              DropdownMenuItem(
                                value: 'todos',
                                child: Text('Todos'),
                              ),
                              DropdownMenuItem(
                                value: 'pendente',
                                child: Text('Pendente'),
                              ),
                              DropdownMenuItem(
                                value: 'em_andamento',
                                child: Text('Em Andamento'),
                              ),
                              DropdownMenuItem(
                                value: 'concluido',
                                child: Text('Concluído'),
                              ),
                              DropdownMenuItem(
                                value: 'cancelado',
                                child: Text('Cancelado'),
                              ),
                            ],
                            onChanged: (value) {
                              setState(() => _selectedStatus = value);
                            },
                            isExpanded: true,
                          ),
                        ),
                      ),
                      Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 16),
                        child: _filteredChamados.isEmpty
                            ? const Center(
                                child: Padding(
                                  padding: EdgeInsets.all(24),
                                  child: Text('Nenhum chamado encontrado'),
                                ),
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
                      ),
                      Padding(
                        padding: const EdgeInsets.all(16),
                        child: SizedBox(
                          width: double.infinity,
                          height: 48,
                          child: ElevatedButton.icon(
                            onPressed: () =>
                                Navigator.pushNamed(context, '/request'),
                            icon: const Icon(Icons.add),
                            label: const Text('Novo Chamado'),
                          ),
                        ),
                      ),
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
      width: 126,
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
    return InkWell(
      borderRadius: BorderRadius.circular(8),
      onTap: () => _showChamadoDetails(chamado),
      child: Container(
        padding: const EdgeInsets.all(12),
        decoration: AppTheme.getCardDecoration(borderRadius: 8),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        chamado.tipoProblema?.nome ?? 'Chamado',
                        style: const TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                          color: AppTheme.primaryColor,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        chamado.descricao,
                        style: const TextStyle(
                          fontSize: 14,
                          fontWeight: FontWeight.w600,
                          color: AppTheme.textPrimaryColor,
                        ),
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ],
                  ),
                ),
                const SizedBox(width: 8),
                _buildStatusChip(chamado),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Icon(Icons.place, size: 14, color: Colors.grey[600]),
                const SizedBox(width: 4),
                Expanded(
                  child: Text(
                    chamado.local?.nome ?? 'Local não informado',
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppTheme.textSecondaryColor,
                    ),
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
                const SizedBox(width: 8),
                Text(
                  DateFormat('dd/MM/yyyy').format(chamado.dataAbertura),
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppTheme.textSecondaryColor,
                  ),
                ),
              ],
            ),
            if (_canManageStatus ||
                _canEditChamado(chamado) ||
                _canDeleteChamado(chamado)) ...[
              const SizedBox(height: 12),
              Align(
                alignment: Alignment.centerRight,
                child: Wrap(
                  alignment: WrapAlignment.end,
                  spacing: 8,
                  runSpacing: 4,
                  children: [
                    if (_canEditChamado(chamado))
                      TextButton.icon(
                        onPressed: () => _showEditChamadoDialog(chamado),
                        icon: const Icon(Icons.edit_outlined, size: 18),
                        label: const Text('Editar'),
                      ),
                    if (_canDeleteChamado(chamado))
                      TextButton.icon(
                        onPressed: () => _confirmDeleteChamado(chamado),
                        icon: const Icon(Icons.delete_outline, size: 18),
                        label: const Text('Excluir'),
                        style: TextButton.styleFrom(
                          foregroundColor: Colors.red,
                        ),
                      ),
                    if (_canManageStatus)
                      TextButton.icon(
                        onPressed: () => _showStatusDialog(chamado),
                        icon: const Icon(Icons.edit_note, size: 18),
                        label: const Text('Alterar status'),
                      ),
                  ],
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }

  Widget _buildStatusChip(Chamado chamado) {
    final color = _getStatusColor(chamado.status);
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.2),
        borderRadius: BorderRadius.circular(4),
      ),
      child: Text(
        chamado.displayStatus,
        style: TextStyle(
          fontSize: 11,
          fontWeight: FontWeight.w600,
          color: color,
        ),
      ),
    );
  }

  Color _getPriorityColor(String? prioridade) {
    if (prioridade == null) return Colors.grey;
    switch (_normalizePriority(prioridade)) {
      case 'alta':
        return Colors.red;
      case 'media':
        return Colors.orange;
      case 'baixa':
        return Colors.green;
      default:
        return Colors.grey;
    }
  }

  Color _getStatusColor(String status) {
    switch (_normalizeStatus(status)) {
      case 'em_andamento':
        return Colors.orange;
      case 'concluido':
        return Colors.green;
      case 'cancelado':
        return Colors.grey;
      case 'pendente':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }
}
