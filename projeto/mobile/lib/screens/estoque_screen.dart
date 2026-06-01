import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/estoque_interno.dart';
import '../services/estoque_service.dart';
import '../theme/app_theme.dart';

class EstoqueScreen extends StatefulWidget {
  const EstoqueScreen({super.key});

  @override
  State<EstoqueScreen> createState() => _EstoqueScreenState();
}

class _EstoqueScreenState extends State<EstoqueScreen> {
  late EstoqueService estoqueService;
  List<EstoqueInterno> itens = [];
  bool isLoading = true;
  bool _loaded = false;
  String? errorMessage;
  String filterStatus = 'todos';

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_loaded) {
      _loaded = true;
      estoqueService = context.read<EstoqueService>();
      _loadEstoque();
    }
  }

  Future<void> _loadEstoque() async {
    setState(() {
      isLoading = true;
      errorMessage = null;
    });

    try {
      final items = await estoqueService.getEstoque();
      if (!mounted) return;

      setState(() {
        itens = items;
        isLoading = false;
      });
    } catch (_) {
      if (!mounted) return;

      setState(() {
        errorMessage = 'Erro ao carregar estoque';
        isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao carregar estoque')),
      );
    }
  }

  List<EstoqueInterno> get filteredItens {
    if (filterStatus == 'todos') return itens;
    return itens.where((item) => item.statusItem == filterStatus).toList();
  }

  void _showFormDialog({EstoqueInterno? item}) {
    final nomeController = TextEditingController(text: item?.nomeItem);
    final descricaoController = TextEditingController(text: item?.descricao);
    final quantidadeController =
        TextEditingController(text: item?.quantidade.toString() ?? '');
    final categoriaController = TextEditingController(text: item?.categoria);
    final localizacaoController =
        TextEditingController(text: item?.localizacao);
    final valorUnitarioController = TextEditingController(
        text: item?.valorUnitario.toStringAsFixed(2) ?? '');
    final codigoController =
        TextEditingController(text: item?.codigoPatrimonio);
    final obsController = TextEditingController(text: item?.observacoes);
    final screenContext = context;
    String selectedStatus = item?.statusItem ?? 'disponivel';

    showDialog(
      context: context,
      builder: (dialogContext) => AlertDialog(
        title: Text(item == null ? 'Novo Item de Estoque' : 'Editar Item'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: nomeController,
                decoration: InputDecoration(
                  labelText: 'Nome do Item',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: descricaoController,
                decoration: InputDecoration(
                  labelText: 'Descrição',
                  border: OutlineInputBorder(),
                ),
                maxLines: 2,
              ),
              const SizedBox(height: 12),
              TextField(
                controller: quantidadeController,
                decoration: InputDecoration(
                  labelText: 'Quantidade',
                  border: OutlineInputBorder(),
                ),
                keyboardType: TextInputType.number,
              ),
              const SizedBox(height: 12),
              TextField(
                controller: categoriaController,
                decoration: InputDecoration(
                  labelText: 'Categoria',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: localizacaoController,
                decoration: InputDecoration(
                  labelText: 'Localização',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: valorUnitarioController,
                decoration: InputDecoration(
                  labelText: 'Valor Unitário (R\$)',
                  border: OutlineInputBorder(),
                ),
                keyboardType: TextInputType.numberWithOptions(decimal: true),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: codigoController,
                decoration: InputDecoration(
                  labelText: 'Código de Patrimônio',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 12),
              DropdownButtonFormField<String>(
                value: selectedStatus,
                decoration: InputDecoration(
                  labelText: 'Status',
                  border: OutlineInputBorder(),
                ),
                items: ['disponivel', 'indisponivel', 'danificado', 'descartado']
                    .map((status) => DropdownMenuItem(
                          value: status,
                          child: Text(_statusText(status)),
                        ))
                    .toList(),
                onChanged: (value) {
                  selectedStatus = value ?? 'disponivel';
                },
              ),
              const SizedBox(height: 12),
              TextField(
                controller: obsController,
                decoration: InputDecoration(
                  labelText: 'Observações',
                  border: OutlineInputBorder(),
                ),
                maxLines: 2,
              ),
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(dialogContext),
            child: const Text('Cancelar'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: AppTheme.primaryColor,
            ),
            onPressed: () async {
              final quantidade = int.tryParse(quantidadeController.text) ?? 0;
              final valorUnitario =
                  double.tryParse(valorUnitarioController.text) ?? 0.0;

              if (item == null) {
                final newItem = await estoqueService.createEstoque(
                  nomeItem: nomeController.text,
                  descricao: descricaoController.text,
                  quantidade: quantidade,
                  categoria: categoriaController.text,
                  localizacao: localizacaoController.text,
                  valorUnitario: valorUnitario,
                  codigoPatrimonio: codigoController.text,
                  statusItem: selectedStatus,
                  observacoes:
                      obsController.text.isEmpty ? null : obsController.text,
                );
                if (newItem != null) {
                  if (!mounted) return;
                  Navigator.pop(dialogContext);
                  _loadEstoque();
                  ScaffoldMessenger.of(screenContext).showSnackBar(
                    const SnackBar(content: Text('Item criado!')),
                  );
                } else if (mounted) {
                  ScaffoldMessenger.of(screenContext).showSnackBar(
                    const SnackBar(content: Text('Erro ao criar item')),
                  );
                }
              } else {
                final updated = await estoqueService.updateEstoque(
                  item.id,
                  nomeItem: nomeController.text,
                  descricao: descricaoController.text,
                  quantidade: quantidade,
                  categoria: categoriaController.text,
                  localizacao: localizacaoController.text,
                  valorUnitario: valorUnitario,
                  codigoPatrimonio: codigoController.text,
                  statusItem: selectedStatus,
                  observacoes:
                      obsController.text.isEmpty ? null : obsController.text,
                );
                if (updated != null) {
                  if (!mounted) return;
                  Navigator.pop(dialogContext);
                  _loadEstoque();
                  ScaffoldMessenger.of(screenContext).showSnackBar(
                    const SnackBar(content: Text('Item atualizado!')),
                  );
                } else if (mounted) {
                  ScaffoldMessenger.of(screenContext).showSnackBar(
                    const SnackBar(content: Text('Erro ao atualizar item')),
                  );
                }
              }
            },
            child: Text(item == null ? 'Criar' : 'Atualizar'),
          ),
        ],
      ),
    ).whenComplete(() {
      nomeController.dispose();
      descricaoController.dispose();
      quantidadeController.dispose();
      categoriaController.dispose();
      localizacaoController.dispose();
      valorUnitarioController.dispose();
      codigoController.dispose();
      obsController.dispose();
    });
  }

  String _statusText(String status) {
    switch (status) {
      case 'disponivel':
        return 'Disponível';
      case 'indisponivel':
        return 'Indisponível';
      case 'danificado':
        return 'Danificado';
      case 'descartado':
        return 'Descartado';
      default:
        return status;
    }
  }

  void _deleteItem(int id) {
    final screenContext = context;

    showDialog(
      context: context,
      builder: (dialogContext) => AlertDialog(
        title: const Text('Confirmação'),
        content: const Text('Tem certeza que deseja deletar este item?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(dialogContext),
            child: const Text('Cancelar'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.red,
            ),
            onPressed: () async {
              Navigator.pop(dialogContext);
              final success = await estoqueService.deleteEstoque(id);
              if (!mounted) return;

              if (success) {
                _loadEstoque();
                ScaffoldMessenger.of(screenContext).showSnackBar(
                  const SnackBar(content: Text('Item deletado!')),
                );
              } else {
                ScaffoldMessenger.of(screenContext).showSnackBar(
                  const SnackBar(content: Text('Erro ao deletar item')),
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
        title: const Text('Gerenciamento de Estoque'),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : errorMessage != null
              ? Center(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(errorMessage!),
                      const SizedBox(height: 12),
                      ElevatedButton(
                        onPressed: _loadEstoque,
                        child: const Text('Tentar novamente'),
                      ),
                    ],
                  ),
                )
          : Column(
              children: [
                // Filtro de status
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: [
                        _buildFilterChip('todos', 'Todos'),
                        const SizedBox(width: 8),
                        _buildFilterChip('disponivel', 'Disponível'),
                        const SizedBox(width: 8),
                        _buildFilterChip('indisponivel', 'Indisponível'),
                        const SizedBox(width: 8),
                        _buildFilterChip('danificado', 'Danificado'),
                        const SizedBox(width: 8),
                        _buildFilterChip('descartado', 'Descartado'),
                      ],
                    ),
                  ),
                ),
                // Lista de itens
                Expanded(
                  child: filteredItens.isEmpty
                      ? Center(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(Icons.inventory_2, size: 64, color: Colors.grey[400]),
                              const SizedBox(height: 16),
                              Text(
                                'Nenhum item encontrado',
                                style: TextStyle(
                                    color: Colors.grey[600], fontSize: 16),
                              ),
                            ],
                          ),
                        )
                      : ListView.builder(
                          padding: const EdgeInsets.all(16),
                          itemCount: filteredItens.length,
                          itemBuilder: (context, index) {
                            final item = filteredItens[index];
                            return Card(
                              margin: const EdgeInsets.only(bottom: 12),
                              child: Padding(
                                padding: const EdgeInsets.all(12),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Expanded(
                                          child: Text(
                                            item.nomeItem,
                                            style: const TextStyle(
                                              fontSize: 16,
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                        ),
                                        PopupMenuButton(
                                          itemBuilder: (context) => [
                                            PopupMenuItem(
                                              child: const Text('Editar'),
                                              onTap: () {
                                                Future.delayed(Duration.zero,
                                                    () {
                                                  _showFormDialog(item: item);
                                                });
                                              },
                                            ),
                                            PopupMenuItem(
                                              child: const Text('Deletar',
                                                  style: TextStyle(
                                                      color: Colors.red)),
                                              onTap: () {
                                                Future.delayed(Duration.zero,
                                                    () {
                                                  _deleteItem(item.id);
                                                });
                                              },
                                            ),
                                          ],
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 8),
                                    Text(item.descricao,
                                        style: TextStyle(
                                            color: Colors.grey[700],
                                            fontSize: 13)),
                                    const SizedBox(height: 8),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Column(
                                          crossAxisAlignment:
                                              CrossAxisAlignment.start,
                                          children: [
                                            Text('Qtd: ${item.quantidade}',
                                                style: const TextStyle(
                                                    fontSize: 12)),
                                            Text(
                                                'R\$ ${item.valorTotal.toStringAsFixed(2)}',
                                                style: const TextStyle(
                                                    fontSize: 12,
                                                    fontWeight:
                                                        FontWeight.w600,
                                                    color: AppTheme
                                                        .primaryColor)),
                                          ],
                                        ),
                                        Chip(
                                          label: Text(
                                            _statusText(item.statusItem),
                                            style: const TextStyle(
                                                color: Colors.white,
                                                fontSize: 11),
                                          ),
                                          backgroundColor: _statusColor(
                                              item.statusItem),
                                          materialTapTargetSize:
                                              MaterialTapTargetSize
                                                  .shrinkWrap,
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                              ),
                            );
                          },
                        ),
                )
              ],
            ),
      floatingActionButton: FloatingActionButton(
        backgroundColor: AppTheme.primaryColor,
        onPressed: () => _showFormDialog(),
        child: const Icon(Icons.add),
      ),
    );
  }

  Widget _buildFilterChip(String value, String label) {
    return FilterChip(
      label: Text(label),
      selected: filterStatus == value,
      onSelected: (_) {
        setState(() => filterStatus = value);
      },
    );
  }

  Color _statusColor(String status) {
    switch (status) {
      case 'disponivel':
        return Colors.green;
      case 'indisponivel':
        return Colors.orange;
      case 'danificado':
        return Colors.red;
      case 'descartado':
        return Colors.grey;
      default:
        return Colors.blue;
    }
  }
}
