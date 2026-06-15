import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../theme/app_theme.dart';
import '../services/chamado_service.dart';
import '../services/equipamento_service.dart';
import '../services/reference_service.dart';
import '../models/equipamento.dart';
import '../models/local.dart';
import '../models/tipo_problema.dart';
import 'app_drawer.dart';

class RequestScreen extends StatefulWidget {
  const RequestScreen({super.key});

  @override
  State<RequestScreen> createState() => _RequestScreenState();
}

class _RequestScreenState extends State<RequestScreen> {
  final _descricaoController = TextEditingController();
  Local? _selectedLocal;
  TipoProblema? _selectedTipo;
  Equipamento? _selectedEquipamento;
  String _selectedTipoChamado = 'interno';
  String? _selectedPrioridade;
  String? _selectedSecaoTecnica;
  String? _selectedComplexidade;
  String? _selectedTipoTrabalho;
  bool _isLoading = false;
  bool _isReferenceLoading = true;
  bool _loaded = false;
  String? _errorMessage;
  String? _successMessage;

  List<Local> _locais = [];
  List<TipoProblema> _tipos = [];
  List<Equipamento> _equipamentos = [];

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_loaded) {
      _loaded = true;
      _loadReferences();
    }
  }

  Future<void> _loadReferences() async {
    final referenceService = context.read<ReferenceService>();
    final equipamentoService = context.read<EquipamentoService>();

    try {
      final locais = await referenceService.getLocais();
      final tipos = await referenceService.getTiposProblema();
      var equipamentos = <Equipamento>[];
      try {
        equipamentos = await equipamentoService.getEquipamentos();
      } catch (_) {}

      if (!mounted) return;
      setState(() {
        _locais = locais;
        _tipos = tipos;
        _equipamentos = equipamentos;
        _errorMessage = null;
        _isReferenceLoading = false;
      });
    } catch (_) {
      if (!mounted) return;
      setState(() {
        _errorMessage = 'Erro ao carregar dados';
        _isReferenceLoading = false;
      });
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text('Erro ao carregar dados')));
    }
  }

  Future<void> _handleCreateChamado() async {
    if (_descricaoController.text.isEmpty ||
        _selectedLocal == null ||
        _selectedTipo == null) {
      setState(() {
        _errorMessage = 'Preencha todos os campos obrigatórios';
      });
      return;
    }

    setState(() {
      _isLoading = true;
      _errorMessage = null;
      _successMessage = null;
    });

    try {
      final chamadoService = context.read<ChamadoService>();
      final chamado = await chamadoService.createChamado(
        descricao: _descricaoController.text,
        idLocal: _selectedLocal!.id,
        idTipo: _selectedTipo!.id,
        idEquipamento: _selectedEquipamento?.id,
        tipoChamado: _selectedTipoChamado,
        prioridade: _selectedPrioridade,
        secaoTecnica: _selectedSecaoTecnica,
        complexidade: _selectedComplexidade,
        tipoTrabalho: _selectedTipoTrabalho,
      );

      if (!mounted) return;

      if (chamado != null) {
        setState(() {
          _successMessage = 'Chamado criado com sucesso!';
          _descricaoController.clear();
          _selectedLocal = null;
          _selectedTipo = null;
          _selectedEquipamento = null;
          _selectedTipoChamado = 'interno';
          _selectedPrioridade = null;
          _selectedSecaoTecnica = null;
          _selectedComplexidade = null;
          _selectedTipoTrabalho = null;
        });

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Chamado criado com sucesso!'),
            backgroundColor: Colors.green,
          ),
        );
        Navigator.of(
          context,
        ).pushNamedAndRemoveUntil('/manage', (route) => false);
      } else {
        setState(() {
          _errorMessage = 'Erro ao criar chamado';
        });
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Erro ao criar chamado')),
        );
      }
    } catch (_) {
      if (!mounted) return;
      setState(() {
        _errorMessage = 'Erro ao criar chamado';
      });
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao criar chamado')),
      );
    } finally {
      if (mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  @override
  void dispose() {
    _descricaoController.dispose();
    super.dispose();
  }

  InputDecoration _fieldDecoration() {
    return InputDecoration(
      filled: true,
      fillColor: AppTheme.inputBackgroundColor,
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(8),
        borderSide: BorderSide.none,
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(8),
        borderSide: BorderSide.none,
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(8),
        borderSide: const BorderSide(
          color: AppTheme.primaryColor,
          width: 2,
        ),
      ),
    );
  }

  Widget _fieldLabel(String text) {
    return Text(
      text,
      style: const TextStyle(
        fontSize: 14,
        fontWeight: FontWeight.bold,
        color: AppTheme.textPrimaryColor,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      drawer: const AppDrawer(),
      appBar: AppBar(
        title: const Text('Novo Chamado'),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: _isReferenceLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    if (_errorMessage != null)
                      Container(
                        padding: const EdgeInsets.all(12),
                        margin: const EdgeInsets.only(bottom: 16),
                        decoration: BoxDecoration(
                          color: Colors.red.withValues(alpha: 0.1),
                          border: Border.all(color: Colors.red),
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(
                          _errorMessage!,
                          style: const TextStyle(color: Colors.red),
                        ),
                      ),
                    if (_successMessage != null)
                      Container(
                        padding: const EdgeInsets.all(12),
                        margin: const EdgeInsets.only(bottom: 16),
                        decoration: BoxDecoration(
                          color: Colors.green.withValues(alpha: 0.1),
                          border: Border.all(color: Colors.green),
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(
                          _successMessage!,
                          style: const TextStyle(color: Colors.green),
                        ),
                      ),
                    const Text(
                      'Local *',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: AppTheme.textPrimaryColor,
                      ),
                    ),
                    const SizedBox(height: 8),
                    DropdownButtonFormField<Local>(
                      value: _selectedLocal,
                      hint: const Text('Selecione um local'),
                      items: _locais
                          .map(
                            (local) => DropdownMenuItem(
                              value: local,
                              child: Text(local.nome),
                            ),
                          )
                          .toList(),
                      onChanged: _isLoading
                          ? null
                          : (value) {
                              setState(() => _selectedLocal = value);
                            },
                      decoration: InputDecoration(
                        filled: true,
                        fillColor: AppTheme.inputBackgroundColor,
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                          borderSide: BorderSide.none,
                        ),
                        enabledBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                          borderSide: BorderSide.none,
                        ),
                        focusedBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                          borderSide: const BorderSide(
                            color: AppTheme.primaryColor,
                            width: 2,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 16),
                    const Text(
                      'Tipo de problema *',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: AppTheme.textPrimaryColor,
                      ),
                    ),
                    const SizedBox(height: 8),
                    DropdownButtonFormField<TipoProblema>(
                      value: _selectedTipo,
                      hint: const Text('Selecione um tipo'),
                      items: _tipos
                          .map(
                            (tipo) => DropdownMenuItem(
                              value: tipo,
                              child: Text(tipo.nome),
                            ),
                          )
                          .toList(),
                      onChanged: _isLoading
                          ? null
                          : (value) {
                              setState(() => _selectedTipo = value);
                            },
                      decoration: InputDecoration(
                        filled: true,
                        fillColor: AppTheme.inputBackgroundColor,
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                          borderSide: BorderSide.none,
                        ),
                        enabledBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                          borderSide: BorderSide.none,
                        ),
                        focusedBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                          borderSide: const BorderSide(
                            color: AppTheme.primaryColor,
                            width: 2,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 16),
                    _fieldLabel('Tipo de chamado *'),
                    const SizedBox(height: 8),
                    DropdownButtonFormField<String>(
                      value: _selectedTipoChamado,
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
                      onChanged: _isLoading
                          ? null
                          : (value) {
                              setState(() {
                                _selectedTipoChamado = value ?? 'interno';
                              });
                            },
                      decoration: _fieldDecoration(),
                    ),
                    const SizedBox(height: 16),
                    _fieldLabel('Equipamento'),
                    const SizedBox(height: 8),
                    DropdownButtonFormField<Equipamento?>(
                      value: _selectedEquipamento,
                      hint: const Text('Sem equipamento vinculado'),
                      items: [
                        const DropdownMenuItem<Equipamento?>(
                          value: null,
                          child: Text('Sem equipamento vinculado'),
                        ),
                        ..._equipamentos.map(
                          (equipamento) => DropdownMenuItem<Equipamento?>(
                            value: equipamento,
                            child: Text(equipamento.toString()),
                          ),
                        ),
                      ],
                      onChanged: _isLoading
                          ? null
                          : (value) {
                              setState(() => _selectedEquipamento = value);
                            },
                      decoration: _fieldDecoration(),
                    ),
                    const SizedBox(height: 20),
                    _fieldLabel('Dados técnicos opcionais'),
                    const SizedBox(height: 8),
                    DropdownButtonFormField<String>(
                      value: _selectedPrioridade,
                      hint: const Text('Prioridade'),
                      items: const [
                        DropdownMenuItem(value: 'baixa', child: Text('Baixa')),
                        DropdownMenuItem(value: 'media', child: Text('Média')),
                        DropdownMenuItem(value: 'alta', child: Text('Alta')),
                      ],
                      onChanged: _isLoading
                          ? null
                          : (value) {
                              setState(() => _selectedPrioridade = value);
                            },
                      decoration: _fieldDecoration(),
                    ),
                    const SizedBox(height: 12),
                    DropdownButtonFormField<String>(
                      value: _selectedSecaoTecnica,
                      hint: const Text('Seção técnica'),
                      items: const [
                        DropdownMenuItem(
                          value: 'eletrica',
                          child: Text('Elétrica'),
                        ),
                        DropdownMenuItem(
                          value: 'hidraulica',
                          child: Text('Hidráulica'),
                        ),
                        DropdownMenuItem(value: 'civil', child: Text('Civil')),
                        DropdownMenuItem(
                          value: 'mecanica',
                          child: Text('Mecânica'),
                        ),
                      ],
                      onChanged: _isLoading
                          ? null
                          : (value) {
                              setState(() => _selectedSecaoTecnica = value);
                            },
                      decoration: _fieldDecoration(),
                    ),
                    const SizedBox(height: 12),
                    DropdownButtonFormField<String>(
                      value: _selectedComplexidade,
                      hint: const Text('Complexidade'),
                      items: const [
                        DropdownMenuItem(
                          value: 'simples',
                          child: Text('Simples'),
                        ),
                        DropdownMenuItem(value: 'media', child: Text('Média')),
                        DropdownMenuItem(
                          value: 'complexa',
                          child: Text('Complexa'),
                        ),
                      ],
                      onChanged: _isLoading
                          ? null
                          : (value) {
                              setState(() => _selectedComplexidade = value);
                            },
                      decoration: _fieldDecoration(),
                    ),
                    const SizedBox(height: 12),
                    DropdownButtonFormField<String>(
                      value: _selectedTipoTrabalho,
                      hint: const Text('Tipo de trabalho'),
                      items: const [
                        DropdownMenuItem(
                          value: 'preventiva',
                          child: Text('Preventiva'),
                        ),
                        DropdownMenuItem(
                          value: 'corretiva',
                          child: Text('Corretiva'),
                        ),
                        DropdownMenuItem(
                          value: 'melhoria',
                          child: Text('Melhoria'),
                        ),
                      ],
                      onChanged: _isLoading
                          ? null
                          : (value) {
                              setState(() => _selectedTipoTrabalho = value);
                            },
                      decoration: _fieldDecoration(),
                    ),
                    const SizedBox(height: 16),
                    const Text(
                      'Descrição *',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: AppTheme.textPrimaryColor,
                      ),
                    ),
                    const SizedBox(height: 8),
                    TextField(
                      controller: _descricaoController,
                      maxLines: 5,
                      enabled: !_isLoading,
                      decoration: InputDecoration(
                        hintText: 'Descreva o problema...',
                        filled: true,
                        fillColor: AppTheme.inputBackgroundColor,
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                          borderSide: BorderSide.none,
                        ),
                        enabledBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                          borderSide: BorderSide.none,
                        ),
                        focusedBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                          borderSide: const BorderSide(
                            color: AppTheme.primaryColor,
                            width: 2,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 24),
                    SizedBox(
                      width: double.infinity,
                      height: 50,
                      child: ElevatedButton(
                        onPressed: _isLoading ? null : _handleCreateChamado,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppTheme.primaryColor,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
                        child: _isLoading
                            ? const SizedBox(
                                height: 20,
                                width: 20,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2,
                                  valueColor: AlwaysStoppedAnimation<Color>(
                                    Colors.white,
                                  ),
                                ),
                              )
                            : const Text(
                                'Criar Chamado',
                                style: TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.w600,
                                  color: Colors.white,
                                ),
                              ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
    );
  }
}
