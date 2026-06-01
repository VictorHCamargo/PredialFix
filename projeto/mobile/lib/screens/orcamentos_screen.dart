import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/orcamento.dart';
import '../services/orcamento_service.dart';
import '../theme/app_theme.dart';

class OrcamentosScreen extends StatefulWidget {
  const OrcamentosScreen({super.key});

  @override
  State<OrcamentosScreen> createState() => _OrcamentosScreenState();
}

class _OrcamentosScreenState extends State<OrcamentosScreen> {
  late OrcamentoService orcamentoService;
  List<Orcamento> orcamentos = [];
  bool isLoading = true;
  bool _loaded = false;
  String? errorMessage;
  String filterStatus = 'todos';

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_loaded) {
      _loaded = true;
      orcamentoService = context.read<OrcamentoService>();
      _loadOrcamentos();
    }
  }

  Future<void> _loadOrcamentos() async {
    setState(() {
      isLoading = true;
      errorMessage = null;
    });

    try {
      final items = await orcamentoService.getOrcamentos();
      if (!mounted) return;

      setState(() {
        orcamentos = items;
        isLoading = false;
      });
    } catch (_) {
      if (!mounted) return;

      setState(() {
        errorMessage = 'Erro ao carregar orcamentos';
        isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao carregar orcamentos')),
      );
    }
  }

  List<Orcamento> get filteredOrcamentos {
    if (filterStatus == 'todos') return orcamentos;
    if (filterStatus == 'pendente') {
      return orcamentos.where((o) => !o.aprovacao).toList();
    }
    return orcamentos.where((o) => o.aprovacao).toList();
  }

  void _showFormDialog() {
    final valorController = TextEditingController();
    final descricaoController = TextEditingController();
    final screenContext = context;
    int? selectedChamadoId;

    showDialog(
      context: context,
      builder: (dialogContext) => AlertDialog(
        title: const Text('Novo Orçamento'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: descricaoController,
                decoration: InputDecoration(
                  labelText: 'Descrição do Serviço',
                  border: OutlineInputBorder(),
                ),
                maxLines: 3,
              ),
              const SizedBox(height: 16),
              TextField(
                controller: valorController,
                decoration: InputDecoration(
                  labelText: 'Valor (R\$)',
                  border: OutlineInputBorder(),
                ),
                keyboardType: TextInputType.numberWithOptions(decimal: true),
              ),
              const SizedBox(height: 16),
              const Text(
                'Selecione um Chamado:',
                style: TextStyle(fontWeight: FontWeight.w600),
              ),
              const SizedBox(height: 8),
              // Simplificado - apenas usar ID do chamado
              TextField(
                decoration: InputDecoration(
                  labelText: 'ID do Chamado',
                  border: OutlineInputBorder(),
                ),
                keyboardType: TextInputType.number,
                onChanged: (value) {
                  selectedChamadoId = int.tryParse(value);
                },
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
              final valor = double.tryParse(valorController.text);
              if (valor == null || selectedChamadoId == null) {
                ScaffoldMessenger.of(screenContext).showSnackBar(
                  const SnackBar(
                      content: Text('Preencha todos os campos corretamente')),
                );
                return;
              }

              final newOrcamento = await orcamentoService.createOrcamento(
                idChamado: selectedChamadoId!,
                valor: valor,
                descricao: descricaoController.text,
              );

              if (newOrcamento != null) {
                if (!mounted) return;
                Navigator.pop(dialogContext);
                _loadOrcamentos();
                ScaffoldMessenger.of(screenContext).showSnackBar(
                  const SnackBar(content: Text('Orçamento criado!')),
                );
              }

              if (newOrcamento == null && mounted) {
                ScaffoldMessenger.of(screenContext).showSnackBar(
                  const SnackBar(content: Text('Erro ao criar orcamento')),
                );
              }
            },
            child: const Text('Criar'),
          ),
        ],
      ),
    ).whenComplete(() {
      valorController.dispose();
      descricaoController.dispose();
    });
  }

  void _approveOrcamento(Orcamento orcamento) async {
    final updated = await orcamentoService.approveOrcamento(orcamento.id);
    if (!mounted) return;

    if (updated != null) {
      _loadOrcamentos();
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Orçamento aprovado!')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Gerenciamento de Orçamentos'),
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
                        onPressed: _loadOrcamentos,
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
                  child: Row(
                    children: [
                      _buildFilterChip('todos', 'Todos'),
                      const SizedBox(width: 8),
                      _buildFilterChip('pendente', 'Pendentes'),
                      const SizedBox(width: 8),
                      _buildFilterChip('aprovado', 'Aprovados'),
                    ],
                  ),
                ),
                // Lista de orçamentos
                Expanded(
                  child: filteredOrcamentos.isEmpty
                      ? Center(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(Icons.receipt_long,
                                  size: 64, color: Colors.grey[400]),
                              const SizedBox(height: 16),
                              Text(
                                'Nenhum orçamento encontrado',
                                style: TextStyle(
                                    color: Colors.grey[600], fontSize: 16),
                              ),
                            ],
                          ),
                        )
                      : ListView.builder(
                          padding: const EdgeInsets.all(16),
                          itemCount: filteredOrcamentos.length,
                          itemBuilder: (context, index) {
                            final orcamento = filteredOrcamentos[index];
                            return Card(
                              margin: const EdgeInsets.only(bottom: 12),
                              child: Padding(
                                padding: const EdgeInsets.all(16),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          'Orçamento #${orcamento.id}',
                                          style: const TextStyle(
                                            fontSize: 16,
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),
                                        Chip(
                                          label: Text(
                                            orcamento.statusText,
                                            style: const TextStyle(
                                                color: Colors.white),
                                          ),
                                          backgroundColor:
                                              orcamento.aprovacao
                                                  ? Colors.green
                                                  : Colors.orange,
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 12),
                                    Text(
                                      'Chamado: #${orcamento.idChamado}',
                                      style: TextStyle(
                                          color: Colors.grey[600],
                                          fontSize: 14),
                                    ),
                                    const SizedBox(height: 8),
                                    Text(
                                      'Descrição: ${orcamento.descricao}',
                                      style: const TextStyle(fontSize: 13),
                                    ),
                                    const SizedBox(height: 12),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          'R\$ ${orcamento.valor.toStringAsFixed(2)}',
                                          style: const TextStyle(
                                            fontSize: 18,
                                            fontWeight: FontWeight.bold,
                                            color: AppTheme.primaryColor,
                                          ),
                                        ),
                                        if (!orcamento.aprovacao)
                                          ElevatedButton.icon(
                                            style: ElevatedButton.styleFrom(
                                              backgroundColor:
                                                  Colors.green,
                                            ),
                                            onPressed: () {
                                              _approveOrcamento(orcamento);
                                            },
                                            icon: const Icon(Icons.check),
                                            label: const Text('Aprovar'),
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
        onPressed: _showFormDialog,
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
}
