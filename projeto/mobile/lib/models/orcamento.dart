class Orcamento {
  final int id;
  final int idChamado;
  final double valor;
  final String descricao;
  final DateTime dataVerificacao;
  final bool aprovacao;

  Orcamento({
    required this.id,
    required this.idChamado,
    required this.valor,
    required this.descricao,
    required this.dataVerificacao,
    required this.aprovacao,
  });

  factory Orcamento.fromJson(Map<String, dynamic> json) {
    return Orcamento(
      id: json['id'] as int,
      idChamado: json['id_chamado'] as int,
      valor: (json['valor'] as num).toDouble(),
      descricao: json['descricao'] as String,
      dataVerificacao: DateTime.parse(json['data_verificacao'] as String),
      aprovacao: json['aprovacao'] as bool,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'id_chamado': idChamado,
      'valor': valor,
      'descricao': descricao,
      'data_verificacao': dataVerificacao.toIso8601String(),
      'aprovacao': aprovacao,
    };
  }

  String get statusText => aprovacao ? 'Aprovado' : 'Pendente';

  @override
  String toString() => 'Orçamento #$id - R\$ ${valor.toStringAsFixed(2)}';
}
