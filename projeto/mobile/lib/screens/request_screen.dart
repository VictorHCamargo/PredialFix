import 'dart:typed_data';

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

import 'app_drawer.dart';

class RequestScreen extends StatefulWidget {
  const RequestScreen({super.key});

  @override
  State<RequestScreen> createState() => _RequestScreenState();
}

class _RequestScreenState extends State<RequestScreen> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
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
      _selectedIncident = null;
      _selectedSection = null;
      _selectedPriority = null;
      _selectedComplexity = null;
      _selectedWorkType = null;
      _pickedImageBytes = null;
    });
  }

  InputDecoration _fieldDecoration(String label, [String? helperText]) {
    return InputDecoration(
      labelText: label,
      helperText: helperText,
      helperStyle: const TextStyle(fontSize: 12, color: Colors.grey),
      filled: true,
      fillColor: const Color(0xFFF8F8F8),
      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: BorderSide.none,
      ),
    );
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
      decoration: _fieldDecoration(label, helperText),
      dropdownColor: Colors.white,
      borderRadius: BorderRadius.circular(12),
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
    const headerColor = Color(0xFFE63946);

    return Scaffold(
      drawer: const AppDrawer(currentPage: MenuPage.createRequest),
      backgroundColor: const Color(0xFFF2F2F4),
      appBar: AppBar(
        backgroundColor: headerColor,
        elevation: 0,
        title: const Text('Abrir Chamados'),
      ),
      body: Column(
        children: [
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Container(
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(24),
                      boxShadow: const [
                        BoxShadow(
                          color: Color.fromRGBO(0, 0, 0, 0.05),
                          blurRadius: 18,
                          offset: Offset(0, 10),
                        ),
                      ],
                    ),
                    padding: const EdgeInsets.all(20),
                    child: Form(
                      key: _formKey,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: [
                          const Text(
                            'Olá, Nome do Professor.',
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: 4),
                          const Text(
                            'Relate o problema abaixo.',
                            style: TextStyle(fontSize: 14, color: Colors.grey),
                          ),
                          const SizedBox(height: 24),
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
                            decoration: _fieldDecoration('Local'),
                            validator: (value) {
                              if (value == null || value.trim().isEmpty) {
                                return 'Informe o local';
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
                            helperText: '(Baixa, Média, Alta, Crítica)',
                            value: _selectedPriority,
                            options: _priorityOptions,
                            onChanged: (value) => setState(() {
                              _selectedPriority = value;
                            }),
                          ),
                          const SizedBox(height: 16),
                          _buildDropdownField(
                            label: 'Nível de Complexidade',
                            helperText: '(Simples, Média, Complexa)',
                            value: _selectedComplexity,
                            options: _complexityOptions,
                            onChanged: (value) => setState(() {
                              _selectedComplexity = value;
                            }),
                          ),
                          const SizedBox(height: 16),
                          _buildDropdownField(
                            label: 'Tipo de Trabalho',
                            helperText: '(Preventiva, Corretiva, Melhoria)',
                            value: _selectedWorkType,
                            options: _workTypeOptions,
                            onChanged: (value) => setState(() {
                              _selectedWorkType = value;
                            }),
                          ),
                          const SizedBox(height: 24),
                          const Text(
                            'Foto',
                            style: TextStyle(
                              fontSize: 14,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                          const SizedBox(height: 10),
                          GestureDetector(
                            onTap: _pickImage,
                            child: Container(
                              height: 170,
                              width: double.infinity,
                              decoration: BoxDecoration(
                                color: const Color(0xFFF3F3F3),
                                borderRadius: BorderRadius.circular(16),
                                border: Border.all(color: const Color(0xFFDDDDDD)),
                              ),
                              child: _pickedImageBytes == null
                                  ? Column(
                                      mainAxisAlignment: MainAxisAlignment.center,
                                      children: [
                                        const Icon(
                                          Icons.cloud_upload_outlined,
                                          size: 44,
                                          color: Colors.grey,
                                        ),
                                        const SizedBox(height: 12),
                                        const Text(
                                          'Faça upload',
                                          style: TextStyle(
                                            fontSize: 16,
                                            fontWeight: FontWeight.w600,
                                            color: Colors.black87,
                                          ),
                                        ),
                                        const SizedBox(height: 8),
                                        const Text(
                                          'Adicione uma imagem',
                                          style: TextStyle(
                                            fontSize: 13,
                                            color: Colors.grey,
                                          ),
                                        ),
                                      ],
                                    )
                                  : ClipRRect(
                                      borderRadius: BorderRadius.circular(16),
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
                  ),
                  const SizedBox(height: 18),
                  SizedBox(
                    height: 52,
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: _submitRequest,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: headerColor,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(24),
                        ),
                      ),
                      child: const Text(
                        'Enviar',
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 24),
                ],
              ),
            ),
          ),
          Container(
            width: double.infinity,
            color: headerColor,
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: const [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'EDIFÍCIO SEDE FIESP',
                      style: TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                        fontSize: 13,
                      ),
                    ),
                    Text(
                      'CENTRAL DE RELACIONAMENTO',
                      style: TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                        fontSize: 13,
                      ),
                    ),
                  ],
                ),
                SizedBox(height: 10),
                Text(
                  'Av. Paulista, 1313, São Paulo/SP\nCEP 01311-930',
                  style: TextStyle(
                    color: Colors.white70,
                    fontSize: 12,
                  ),
                ),
                SizedBox(height: 8),
                Text(
                  'Tel 3222-0039 | Telefones/WhatsApp\n0800-055-1000 (Interior de SP,\nsomente tarifa local)',
                  style: TextStyle(
                    color: Colors.white70,
                    fontSize: 12,
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
