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

  final List<String> _priorityOptions = [
    'Baixa',
    'Média',
    'Alta',
    'Crítica',
  ];

  final List<String> _complexityOptions = [
    'Simples',
    'Média',
    'Complexa',
  ];

  final List<String> _workTypeOptions = [
    'Preventiva',
    'Corretiva',
    'Melhoria',
  ];

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
      initialValue: value,
      decoration: AppTheme.getFieldDecoration(label, helperText: helperText),
      dropdownColor: AppTheme.cardBackgroundColor,
      borderRadius: BorderRadius.circular(AppTheme.radiusMedium),
      items: options
          .map((option) => DropdownMenuItem<String>(
                value: option,
                child: Text(option),
              ))
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
      appBar: AppBar(
        title: const Text('Abrir Chamado'),
      ),
      body: Column(
        children: [
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(AppTheme.paddingLarge),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    Container(
                      decoration: AppTheme.getCardDecoration(),
                      padding: const EdgeInsets.all(AppTheme.paddingLarge),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text(
                            'Olá, Docente!',
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                              color: AppTheme.textPrimaryColor,
                            ),
                          ),
                          const SizedBox(height: 4),
                          const Text(
                            'Relate o problema abaixo para abrir um novo chamado.',
                            style: TextStyle(
                              fontSize: 14,
                              color: AppTheme.textSecondaryColor,
                            ),
                          ),
                          const SizedBox(height: AppTheme.paddingLarge),
                          _buildDropdownField(
                            label: 'Tipo de Incidente',
                            value: _selectedIncident,
                            options: _incidentOptions,
                            onChanged: (value) => setState(() {
                              _selectedIncident = value;
                            }),
                          ),
                          const SizedBox(height: 16),
                          TextFormField(
                            controller: _locationController,
                            decoration: AppTheme.getFieldDecoration('Local do Problema'),
                            validator: (value) {
                              if (value == null || value.trim().isEmpty) {
                                return 'Informe o local';
                              }
                              return null;
                            },
                          ),
                          const SizedBox(height: 16),
                          TextFormField(
                            controller: _descriptionController,
                            maxLines: 3,
                            decoration: InputDecoration(
                              labelText: 'Descrição Detalhada',
                              filled: true,
                              fillColor: AppTheme.inputBackgroundColor,
                              contentPadding: const EdgeInsets.symmetric(
                                horizontal: 16,
                                vertical: 14,
                              ),
                              border: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(AppTheme.radiusMedium),
                                borderSide: BorderSide.none,
                              ),
                            ),
                            validator: (value) {
                              if (value == null || value.trim().isEmpty) {
                                return 'Descreva o problema';
                              }
                              return null;
                            },
                          ),
                          const SizedBox(height: 16),
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
                          _buildDropdownField(
                            label: 'Nível de Prioridade',
                            value: _selectedPriority,
                            options: _priorityOptions,
                            onChanged: (value) => setState(() {
                              _selectedPriority = value;
                            }),
                          ),
                          const SizedBox(height: 16),
                          _buildDropdownField(
                            label: 'Nível de Complexidade',
                            value: _selectedComplexity,
                            options: _complexityOptions,
                            onChanged: (value) => setState(() {
                              _selectedComplexity = value;
                            }),
                          ),
                          const SizedBox(height: 16),
                          _buildDropdownField(
                            label: 'Tipo de Trabalho',
                            value: _selectedWorkType,
                            options: _workTypeOptions,
                            onChanged: (value) => setState(() {
                              _selectedWorkType = value;
                            }),
                          ),
                          const SizedBox(height: AppTheme.paddingLarge),
                          const Text(
                            'Adicionar Foto (Opcional)',
                            style: TextStyle(
                              fontSize: 14,
                              fontWeight: FontWeight.w600,
                              color: AppTheme.textPrimaryColor,
                            ),
                          ),
                          const SizedBox(height: 10),
                          GestureDetector(
                            onTap: _pickImage,
                            child: Container(
                              height: 170,
                              width: double.infinity,
                              decoration: BoxDecoration(
                                color: AppTheme.inputBackgroundColor,
                                borderRadius: BorderRadius.circular(AppTheme.radiusMedium),
                                border: Border.all(
                                  color: AppTheme.textSecondaryColor.withOpacity(0.3),
                                ),
                              ),
                              child: _pickedImageBytes == null
                                  ? Column(
                                      mainAxisAlignment: MainAxisAlignment.center,
                                      children: [
                                        Icon(
                                          Icons.cloud_upload_outlined,
                                          size: 44,
                                          color: AppTheme.primaryColor.withOpacity(0.6),
                                        ),
                                        const SizedBox(height: 12),
                                        const Text(
                                          'Faça upload',
                                          style: TextStyle(
                                            fontSize: 16,
                                            fontWeight: FontWeight.w600,
                                            color: AppTheme.textPrimaryColor,
                                          ),
                                        ),
                                        const SizedBox(height: 8),
                                        const Text(
                                          'Adicione uma imagem da área',
                                          style: TextStyle(
                                            fontSize: 13,
                                            color: AppTheme.textSecondaryColor,
                                          ),
                                        ),
                                      ],
                                    )
                                  : ClipRRect(
                                      borderRadius: BorderRadius.circular(AppTheme.radiusMedium),
                                      child: Image.memory(
                                        _pickedImageBytes!,
                                        fit: BoxFit.cover,
                                      ),
                                    ),
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 24),
                    SizedBox(
                      height: 52,
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _submitRequest,
                        child: const Text('Enviar Chamado'),
                      ),
                    ),
                    const SizedBox(height: 12),
                  ],
                ),
              ),
            ),
          ),
          Container(
            width: double.infinity,
            color: AppTheme.primaryColor,
            padding: const EdgeInsets.symmetric(
              horizontal: AppTheme.paddingMedium,
              vertical: AppTheme.paddingMedium,
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: const [
                Text(
                  'SENAI - Central de Relacionamento',
                  style: TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                    fontSize: 12,
                  ),
                ),
                SizedBox(height: 8),
                Text(
                  'Av. Paulista, 1313 - São Paulo/SP\nCEP 01311-930\nTel: (11) 3222-0039 | WhatsApp: 0800-055-1000',
                  style: TextStyle(
                    color: Colors.white70,
                    fontSize: 11,
                    height: 1.6,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
