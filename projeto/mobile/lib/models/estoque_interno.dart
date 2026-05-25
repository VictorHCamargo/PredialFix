class EstoqueInterno {
  final int id;
  final String nomeItem;
  final String descricao;
  final int quantidade;
  final String categoria;
  final String localizacao;
  final double valorUnitario;
  final double valorTotal;
  final String codigoPatrimonio;
  final String statusItem; // disponivel, indisponivel, danificado, descartado
  final DateTime dataEntrada;
  final DateTime? dataSaida;
  final String? observacoes;

  EstoqueInterno({
    required this.id,
    required this.nomeItem,
    required this.descricao,
    required this.quantidade,
    required this.categoria,
    required this.localizacao,
    required this.valorUnitario,
    required this.valorTotal,
    required this.codigoPatrimonio,
    required this.statusItem,
    required this.dataEntrada,
    this.dataSaida,
    this.observacoes,
  });

  factory EstoqueInterno.fromJson(Map<String, dynamic> json) {
    return EstoqueInterno(
      id: json['id'] as int,
      nomeItem: json['nome_item'] as String,
      descricao: json['descricao'] as String,
      quantidade: json['quantidade'] as int,
      categoria: json['categoria'] as String,
      localizacao: json['localizacao'] as String,
      valorUnitario: (json['valor_unitario'] as num).toDouble(),
      valorTotal: (json['valor_total'] as num).toDouble(),
      codigoPatrimonio: json['codigo_patrimonio'] as String,
      statusItem: json['status_item'] as String,
      dataEntrada: DateTime.parse(json['data_entrada'] as String),
      dataSaida: json['data_saida'] != null
          ? DateTime.parse(json['data_saida'] as String)
          : null,
      observacoes: json['observacoes'] as String?,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nome_item': nomeItem,
      'descricao': descricao,
      'quantidade': quantidade,
      'categoria': categoria,
      'localizacao': localizacao,
      'valor_unitario': valorUnitario,
      'valor_total': valorTotal,
      'codigo_patrimonio': codigoPatrimonio,
      'status_item': statusItem,
      'data_entrada': dataEntrada.toIso8601String(),
      'data_saida': dataSaida?.toIso8601String(),
      'observacoes': observacoes,
    };
  }

  @override
  String toString() => '$nomeItem (Qtd: $quantidade)';
}
