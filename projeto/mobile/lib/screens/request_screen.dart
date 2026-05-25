import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../theme/app_theme.dart';
import '../services/chamado_service.dart';
import '../services/reference_service.dart';
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
  bool _isLoading = false;
  String? _errorMessage;
  String? _successMessage;
  
  List<Local> _locais = [];
  List<TipoProblema> _tipos = [];

  @override
  void initState() {
    super.initState();
    _loadReferences();
  }

  Future<void> _loadReferences() async {
    final referenceService = context.read<ReferenceService>();
    
    try {
      final locais = await referenceService.getLocais();
      final tipos = await referenceService.getTiposProblema();
      
      setState(() {
        _locais = locais;
        _tipos = tipos;
      });
    } catch (e) {
      setState(() {
        _errorMessage = 'Erro ao carregar dados: \$e';
      });
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
      );

      if (!mounted) return;

      if (chamado != null) {
        setState(() {
          _successMessage = 'Chamado criado com sucesso!';
          _descricaoController.clear();
          _selectedLocal = null;
          _selectedTipo = null;
        });

        Future.delayed(const Duration(seconds: 2), () {
          if (mounted) {
            Navigator.of(context)
                .pushNamedAndRemoveUntil('/manage', (route) => false);
          }
        });
      } else {
        setState(() {
          _errorMessage = 'Erro ao criar chamado';
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Erro: \${e.toString()}';
      });
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

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      drawer: const AppDrawer(),
      appBar: AppBar(
        title: const Text('Novo Chamado'),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: SingleChildScrollView(
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
                    color: Colors.red.withOpacity(0.1),
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
                    color: Colors.green.withOpacity(0.1),
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
                    .map((local) => DropdownMenuItem(
                          value: local,
                          child: Text(local.nome),
                        ))
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
                'Tipo de Problema *',
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
                    .map((tipo) => DropdownMenuItem(
                          value: tipo,
                          child: Text(tipo.nome),
                        ))
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
