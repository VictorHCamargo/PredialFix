import 'dart:typed_data';

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

import '../theme/app_theme.dart';
import 'app_drawer.dart';

class RequestScreen extends StatefulWidget {
  const RequestScreen({super.key});

  @override
  State<RequestScreen> createState() => _RequestScreenState();
}

class _RequestScreenState extends State<RequestScreen> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final TextEditingController _descriptionController = TextEditingController();
  final TextEditingController _locationController = TextEditingController();
  final ImagePicker _picker = ImagePicker();

  String? _selectedIncident;
  String? _selectedSection;
  String? _selectedPriority;
  String? _selectedComplexity;
  String? _selectedWorkType;
  Uint8List? _pickedImageBytes;

  final List<String> _incidentOptions = [
    'Elétrica',
    'Hidráulica',
    'Civil',
    'Infraestrutura',
    'Outro',
  ];

  final List<String> _sectionOptions = [
    'Elétrica',
    'Hidráulica',
    'Civil',
    'Ar Condicionado',
    'Outro',
  ];

  final List<String> _priorityOptions = ['Baixa', 'Média', 'Alta', 'Crítica'];

  final List<String> _complexityOptions = ['Simples', 'Média', 'Complexa'];

  final List<String> _workTypeOptions = ['Preventiva', 'Corretiva', 'Melhoria'];

  @override
  void dispose() {
    _descriptionController.dispose();
    _locationController.dispose();
    super.dispose();
  }

  Future<void> _pickImage() async {
    final image = await _picker.pickImage(source: ImageSource.gallery);
    if (image != null) {
      final bytes = await image.readAsBytes();
      setState(() {
        _pickedImageBytes = bytes;
      });
    }
  }

  void _submitRequest() {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Chamado enviado com sucesso!')),
    );

    _formKey.currentState?.reset();
    setState(() {
      _descriptionController.clear();
      _locationController.clear();
      _selectedIncident = null;
      _selectedSection = null;
      _selectedPriority = null;
      _selectedComplexity = null;
      _selectedWorkType = null;
      _pickedImageBytes = null;
    });
  }

  Widget _buildDropdownField({
    required String label,
    required String? value,
    required List<String> options,
    required ValueChanged<String?> onChanged,
    String? helperText,
  }) {
    return DropdownButtonFormField<String>(
      value: value,
      decoration: InputDecoration(
        labelText: label,
        helperText: helperText,
        filled: true,
        fillColor: AppTheme.inputBackgroundColor,
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 16,
          vertical: 14,
        ),
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
          borderSide: const BorderSide(color: AppTheme.primaryColor, width: 2),
        ),
        labelStyle: const TextStyle(
          fontSize: 14,
          color: AppTheme.textSecondaryColor,
        ),
        helperStyle: const TextStyle(
          fontSize: 12,
          color: AppTheme.textSecondaryColor,
        ),
      ),
      dropdownColor: AppTheme.cardBackgroundColor,
      borderRadius: BorderRadius.circular(8),
      items: options
          .map(
            (option) =>
                DropdownMenuItem<String>(value: option, child: Text(option)),
          )
          .toList(),
      onChanged: onChanged,
      validator: (value) {
        if (value == null || value.isEmpty) {
          return 'Selecione uma opção';
        }
        return null;
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: const AppDrawer(currentPage: MenuPage.createRequest),
      backgroundColor: AppTheme.backgroundColor,
      appBar: AppBar(title: const Text('Abrir Chamados'), elevation: 4),
      body: Column(
        children: [
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Saudação
                    const Text(
                      'Olá, [Nome do Professor].',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w600,
                        color: AppTheme.textPrimaryColor,
                      ),
                    ),
                    const SizedBox(height: 4),
                    const Text(
                      'Relate o problema abaixo.',
                      style: TextStyle(
                        fontSize: 14,
                        color: AppTheme.textSecondaryColor,
                      ),
                    ),
                    const SizedBox(height: 24),

                    // Tipo de Incidente
                    _buildDropdownField(
                      label: 'Tipo de Incidente',
                      value: _selectedIncident,
                      options: _incidentOptions,
                      onChanged: (value) => setState(() {
                        _selectedIncident = value;
                      }),
                    ),
                    const SizedBox(height: 16),

                    // Local
                    TextFormField(
                      controller: _locationController,
                      decoration: InputDecoration(
                        labelText: 'Local',
                        filled: true,
                        fillColor: AppTheme.inputBackgroundColor,
                        contentPadding: const EdgeInsets.symmetric(
                          horizontal: 16,
                          vertical: 14,
                        ),
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
                        labelStyle: const TextStyle(
                          fontSize: 14,
                          color: AppTheme.textSecondaryColor,
                        ),
                      ),
                      validator: (value) {
                        if (value == null || value.trim().isEmpty) {
                          return 'Informe o local';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),

                    // Seção Técnica
                    _buildDropdownField(
                      label: 'Seção Técnica',
                      helperText: '(Elétrica, Hidráulica, Civil, etc)',
                      value: _selectedSection,
                      options: _sectionOptions,
                      onChanged: (value) => setState(() {
                        _selectedSection = value;
                      }),
                    ),
                    const SizedBox(height: 16),

                    // Nível de Prioridade
                    _buildDropdownField(
                      label: 'Nível de Prioridade',
                      value: _selectedPriority,
                      options: _priorityOptions,
                      onChanged: (value) => setState(() {
                        _selectedPriority = value;
                      }),
                    ),
                    const SizedBox(height: 16),

                    // Nível de Complexidade
                    _buildDropdownField(
                      label: 'Nível de Complexidade',
                      value: _selectedComplexity,
                      options: _complexityOptions,
                      onChanged: (value) => setState(() {
                        _selectedComplexity = value;
                      }),
                    ),
                    const SizedBox(height: 16),

                    // Tipo de Trabalho
                    _buildDropdownField(
                      label: 'Tipo de Trabalho',
                      value: _selectedWorkType,
                      options: _workTypeOptions,
                      onChanged: (value) => setState(() {
                        _selectedWorkType = value;
                      }),
                    ),
                    const SizedBox(height: 16),

                    // Descrição
                    TextFormField(
                      controller: _descriptionController,
                      maxLines: 4,
                      decoration: InputDecoration(
                        labelText: 'Descrição Detalhada',
                        filled: true,
                        fillColor: AppTheme.inputBackgroundColor,
                        contentPadding: const EdgeInsets.symmetric(
                          horizontal: 16,
                          vertical: 14,
                        ),
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
                        labelStyle: const TextStyle(
                          fontSize: 14,
                          color: AppTheme.textSecondaryColor,
                        ),
                        alignLabelWithHint: true,
                      ),
                      validator: (value) {
                        if (value == null || value.trim().isEmpty) {
                          return 'Descreva o problema';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 24),

                    // Foto (Opcional)
                    const Text(
                      'Foto',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w600,
                        color: AppTheme.textPrimaryColor,
                      ),
                    ),
                    const SizedBox(height: 12),
                    GestureDetector(
                      onTap: _pickImage,
                      child: Container(
                        height: 140,
                        width: double.infinity,
                        decoration: BoxDecoration(
                          color: AppTheme.inputBackgroundColor,
                          borderRadius: BorderRadius.circular(8),
                          border: Border.all(
                            color: AppTheme.textSecondaryColor.withOpacity(0.2),
                            width: 1,
                          ),
                        ),
                        child: _pickedImageBytes == null
                            ? Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  Icon(
                                    Icons.image_outlined,
                                    size: 48,
                                    color: AppTheme.primaryColor.withOpacity(
                                      0.5,
                                    ),
                                  ),
                                  const SizedBox(height: 12),
                                  const Text(
                                    'Faça upload',
                                    style: TextStyle(
                                      fontSize: 14,
                                      fontWeight: FontWeight.w600,
                                      color: AppTheme.textPrimaryColor,
                                    ),
                                  ),
                                ],
                              )
                            : ClipRRect(
                                borderRadius: BorderRadius.circular(8),
                                child: Image.memory(
                                  _pickedImageBytes!,
                                  fit: BoxFit.cover,
                                ),
                              ),
                      ),
                    ),
                    const SizedBox(height: 24),

                    // Botão Enviar
                    SizedBox(
                      width: double.infinity,
                      height: 52,
                      child: ElevatedButton(
                        onPressed: _submitRequest,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppTheme.primaryColor,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
                        child: const Text(
                          'Enviar',
                          style: TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.w600,
                            color: Colors.white,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 20),
                  ],
                ),
              ),
            ),
          ),
          // Footer SENAI
          Container(
            width: double.infinity,
            color: AppTheme.primaryColor,
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'HORÁRIO DE ATENDIMENTO',
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 11,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                          SizedBox(height: 4),
                          Text(
                            'Seg a Sex, 8h às 18h\nSábado: 9h às 13h',
                            style: TextStyle(
                              color: Colors.white70,
                              fontSize: 11,
                              height: 1.4,
                            ),
                          ),
                        ],
                      ),
                    ),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: const [
                          Text(
                            'CONTATO DE RELACIONAMENTO',
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 11,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                          SizedBox(height: 4),
                          Text(
                            'Tel: (11) 3222-0039\nWhatsApp: 0800-055-1000',
                            style: TextStyle(
                              color: Colors.white70,
                              fontSize: 11,
                              height: 1.4,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
