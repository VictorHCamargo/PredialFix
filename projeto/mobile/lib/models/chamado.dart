class Chamado {
  final String id;
  final String tipo;
  final String descricao;
  final String local;
  final String data;
  final String status;

  Chamado({
    required this.id,
    required this.tipo,
    required this.descricao,
    required this.local,
    required this.data,
    required this.status,
  });

  // Dados de exemplo
  static List<Chamado> exemplosChamados() {
    return [
      Chamado(
        id: '1',
        tipo: 'Elétrica',
        descricao: 'Tomada em Curto Circuito',
        local: 'Bloco A, Sala 1',
        data: '02/01/2026',
        status: 'Em Andamento',
      ),
      Chamado(
        id: '2',
        tipo: 'Hidráulica',
        descricao: 'Vazamento de Água',
        local: 'Bloco B, Sala 5',
        data: '01/01/2026',
        status: 'Concluído',
      ),
      Chamado(
        id: '3',
        tipo: 'Elétrica',
        descricao: 'Lâmpada Queimada',
        local: 'Corredor 1',
        data: '31/12/2025',
        status: 'Em Andamento',
      ),
      Chamado(
        id: '4',
        tipo: 'Limpeza',
        descricao: 'Limpeza Geral',
        local: 'Bloco C',
        data: '30/12/2025',
        status: 'Pendente',
      ),
    ];
  }
}
