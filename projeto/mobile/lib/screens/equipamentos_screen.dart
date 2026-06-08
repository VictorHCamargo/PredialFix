import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/equipamento.dart';
import '../services/equipamento_service.dart';
import '../theme/app_theme.dart';

class EquipamentosScreen extends StatefulWidget {
  const EquipamentosScreen({super.key});

  @override
  State<EquipamentosScreen> createState() => _EquipamentosScreenState();
}

class _EquipamentosScreenState extends State<EquipamentosScreen> {
  late EquipamentoService equipamentoService;
  List<Equipamento> equipamentos = [];
  bool isLoading = true;
  bool _loaded = false;
  String? _errorMessage;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_loaded) {
      _loaded = true;
      equipamentoService = context.read<EquipamentoService>();
      _loadEquipamentos();
    }
  }

  Future<void> _loadEquipamentos() async {
    setState(() {
      isLoading = true;
      _errorMessage = null;
    });

    try {
      final items = await equipamentoService.getEquipamentos();
      if (!mounted) return;
      setState(() {
        equipamentos = items;
        isLoading = false;
      });
    } catch (_) {
      if (!mounted) return;
      setState(() {
        _errorMessage = 'Erro ao carregar equipamentos';
        isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao carregar equipamentos')),
      );
    }
  }

  Future<void> _showFormDialog({Equipamento? equipamento}) async {
    final tagController = TextEditingController(
      text: equipamento?.tagIdentificacao,
    );
    final nomeController = TextEditingController(text: equipamento?.nome);
    final marcaController = TextEditingController(text: equipamento?.marca);
    final screenContext = context;
    String selectedStatus = equipamento?.status ?? 'ativo';

    try {
      await showDialog(
        context: context,
        builder: (context) => AlertDialog(
          title: Text(
            equipamento == null ? 'Novo Equipamento' : 'Editar Equipamento',
          ),
          content: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                TextField(
                  controller: tagController,
                  decoration: InputDecoration(
                    labelText: 'Tag de Identificação',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: nomeController,
                  decoration: InputDecoration(
                    labelText: 'Nome do Equipamento',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: marcaController,
                  decoration: InputDecoration(
                    labelText: 'Marca',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 16),
                DropdownButton<String>(
                  value: selectedStatus,
                  isExpanded: true,
                  items: ['ativo', 'manutencao', 'inativo']
                      .map(
                        (status) => DropdownMenuItem(
                          value: status,
                          child: Text(
                            status == 'ativo'
                                ? 'Ativo'
                                : status == 'manutencao'
                                ? 'Em Manutenção'
                                : 'Inativo',
                          ),
                        ),
                      )
                      .toList(),
                  onChanged: (value) {
                    selectedStatus = value ?? 'ativo';
                  },
                ),
              ],
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Cancelar'),
            ),
            ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: AppTheme.primaryColor,
              ),
              onPressed: () async {
                if (equipamento == null) {
                  final navigator = Navigator.of(context);
                  final messenger = ScaffoldMessenger.of(screenContext);
                  final newEquip = await equipamentoService.createEquipamento(
                    tagIdentificacao: tagController.text,
                    nome: nomeController.text,
                    marca: marcaController.text,
                    status: selectedStatus,
                  );
                  if (!mounted) return;
                  if (newEquip != null) {
                    navigator.pop();
                    _loadEquipamentos();
                    messenger.showSnackBar(
                      const SnackBar(content: Text('Equipamento criado!')),
                    );
                  } else {
                    messenger.showSnackBar(
                      const SnackBar(
                        content: Text('Erro ao criar equipamento'),
                      ),
                    );
                  }
                } else {
                  final navigator = Navigator.of(context);
                  final messenger = ScaffoldMessenger.of(screenContext);
                  final updated = await equipamentoService.updateEquipamento(
                    equipamento.id,
                    tagIdentificacao: tagController.text,
                    nome: nomeController.text,
                    marca: marcaController.text,
                    status: selectedStatus,
                  );
                  if (!mounted) return;
                  if (updated != null) {
                    navigator.pop();
                    _loadEquipamentos();
                    messenger.showSnackBar(
                      const SnackBar(content: Text('Equipamento atualizado!')),
                    );
                  } else {
                    messenger.showSnackBar(
                      const SnackBar(
                        content: Text('Erro ao atualizar equipamento'),
                      ),
                    );
                  }
                }
              },
              child: Text(equipamento == null ? 'Criar' : 'Atualizar'),
            ),
          ],
        ),
      );
    } finally {
      tagController.dispose();
      nomeController.dispose();
      marcaController.dispose();
    }
  }

  void _deleteEquipamento(int id) {
    final screenContext = context;

    showDialog(
      context: context,
      builder: (dialogContext) => AlertDialog(
        title: const Text('Confirmação'),
        content: const Text('Tem certeza que deseja deletar este equipamento?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(dialogContext),
            child: const Text('Cancelar'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            onPressed: () async {
              Navigator.pop(dialogContext);
              final messenger = ScaffoldMessenger.of(screenContext);
              final success = await equipamentoService.deleteEquipamento(id);
              if (!mounted) return;

              if (success) {
                _loadEquipamentos();
                messenger.showSnackBar(
                  const SnackBar(content: Text('Equipamento deletado!')),
                );
              } else {
                messenger.showSnackBar(
                  const SnackBar(content: Text('Erro ao deletar equipamento')),
                );
              }
            },
            child: const Text('Deletar'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Gerenciamento de Equipamentos'),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: isLoading
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
                      onPressed: _loadEquipamentos,
                      child: const Text('Tentar novamente'),
                    ),
                  ],
                ),
              ),
            )
          : equipamentos.isEmpty
          ? Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.devices_other, size: 64, color: Colors.grey[400]),
                  const SizedBox(height: 16),
                  Text(
                    'Nenhum equipamento cadastrado',
                    style: TextStyle(color: Colors.grey[600], fontSize: 16),
                  ),
                ],
              ),
            )
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: equipamentos.length,
              itemBuilder: (context, index) {
                final equip = equipamentos[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: ListTile(
                    leading: Icon(Icons.build, color: AppTheme.primaryColor),
                    title: Text(equip.nome),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const SizedBox(height: 4),
                        Text(
                          'Tag: ${equip.tagIdentificacao}',
                          style: const TextStyle(fontSize: 12),
                        ),
                        Text(
                          'Marca: ${equip.marca}',
                          style: const TextStyle(fontSize: 12),
                        ),
                        const SizedBox(height: 4),
                        Chip(
                          label: Text(
                            equip.status == 'ativo'
                                ? 'Ativo'
                                : equip.status == 'manutencao'
                                ? 'Em Manutenção'
                                : 'Inativo',
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 11,
                            ),
                          ),
                          backgroundColor: equip.status == 'ativo'
                              ? Colors.green
                              : equip.status == 'manutencao'
                              ? Colors.orange
                              : Colors.red,
                          materialTapTargetSize:
                              MaterialTapTargetSize.shrinkWrap,
                        ),
                      ],
                    ),
                    trailing: PopupMenuButton(
                      itemBuilder: (context) => [
                        PopupMenuItem(
                          child: const Text('Editar'),
                          onTap: () {
                            WidgetsBinding.instance.addPostFrameCallback((_) {
                              if (mounted) {
                                _showFormDialog(equipamento: equip);
                              }
                            });
                          },
                        ),
                        PopupMenuItem(
                          child: const Text(
                            'Deletar',
                            style: TextStyle(color: Colors.red),
                          ),
                          onTap: () {
                            WidgetsBinding.instance.addPostFrameCallback((_) {
                              if (mounted) {
                                _deleteEquipamento(equip.id);
                              }
                            });
                          },
                        ),
                      ],
                    ),
                    onTap: () {
                      _showFormDialog(equipamento: equip);
                    },
                  ),
                );
              },
            ),
      floatingActionButton: FloatingActionButton(
        backgroundColor: AppTheme.primaryColor,
        onPressed: () => _showFormDialog(),
        child: const Icon(Icons.add),
      ),
    );
  }
}
