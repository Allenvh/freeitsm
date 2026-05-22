<?php
/**
 * Português (Brasil) (pt-BR) — Process Mapper module strings.
 * Falls back per-key to lang/en/process-mapper.php for anything missing.
 */
return [
    'title' => 'Mapeador de processos',

    'nav' => [
        'processes' => 'Processos',
        'settings'  => 'Configurações',
        'help'      => 'Ajuda',
    ],

    'sidebar' => [
        'new_process'        => '+ Novo processo',
        'search_placeholder' => 'Pesquisar processos…',
        'no_processes_yet'   => 'Ainda não há processos',
    ],

    'toolbar' => [
        'process'   => 'Processo',
        'decision'  => 'Decisão',
        'terminal'  => 'Início/Fim',
        'document'  => 'Documento',
        'connect'   => 'Conectar',
        'group'     => 'Grupo',
        'lane'      => 'Raia',
        'export'    => 'Exportar',
        'save'      => 'Salvar',
    ],

    'context' => [
        'create_new' => 'Criar novo…',
    ],

    'shapes' => [
        'rectangle'     => 'Retângulo',
        'rounded'       => 'Arredondado',
        'pill'          => 'Pílula',
        'circle'        => 'Círculo',
        'diamond'       => 'Losango',
        'parallelogram' => 'Paralelogramo',
        'trapezoid'     => 'Trapézio',
        'hexagon'       => 'Hexágono',
        'document'      => 'Documento',
        'cylinder'      => 'Cilindro',
        'cloud'         => 'Nuvem',
        'subroutine'    => 'Sub-rotina',
    ],

    'settings' => [
        'title'            => 'Tipos de etapa',
        'intro'            => 'Defina os tipos de bloco disponíveis na barra de ferramentas do Mapeador de processos. Cada tipo é um nome, uma forma e uma cor.',
        'add_type'         => 'Adicionar tipo',
        'col_order'        => 'Ordem',
        'col_shape'        => 'Forma',
        'col_name'         => 'Nome',
        'col_colour'       => 'Cor',
        'col_active'       => 'Ativo',
        'col_actions'      => 'Ações',
        'none'             => 'Ainda não há tipos de etapa',
        'builtin'          => 'Integrado',
        'yes'              => 'Sim',
        'no'               => 'Não',
        'add_title'        => 'Adicionar tipo de etapa',
        'edit_title'       => 'Editar tipo de etapa',
        'field_name'       => 'Nome',
        'field_shape'      => 'Forma',
        'field_colour'     => 'Cor',
        'field_active'     => 'Ativo',
        'name_placeholder' => 'ex. Banco de dados, Etapa manual',
        'name_required'    => 'O nome é obrigatório',
        'saved'            => 'Salvo',
        'deleted'          => 'Excluído',
        'delete_confirm'   => 'Excluir este tipo de etapa?',
    ],

    'autosave' => [
        'label'   => 'Salvamento automático',
        'saved'   => 'Salvo',
        'unsaved' => 'Não salvo',
        'unsaved_changes' => 'Alterações não salvas',
        'saving'  => 'Salvando…',
        'failed'  => 'Falha ao salvar —',
        'retry'   => 'tentar novamente',
        'off'     => 'Salvamento automático desativado',
        'tooltip' => 'Salva automaticamente alguns segundos após você parar de editar',
    ],

    'detail' => [
        'step_title'   => 'Detalhes da etapa',
        'group_title'  => 'Detalhes do grupo',
        'lane_title'   => 'Detalhes da raia',
        'label'        => 'Rótulo',
        'type'         => 'Tipo',
        'colour'       => 'Cor',
        'gradient'     => 'Gradiente',
        'description'  => 'Descrição',
        'position'     => 'Posição',
        'size'         => 'Tamanho',
        'height'       => 'Altura',
        'order'        => 'Ordem (de cima para baixo)',
        'connectors'   => 'Conectores',
        'no_connectors'=> 'Sem conectores',
        'step_type' => [
            'process'  => 'Processo',
            'decision' => 'Decisão',
            'terminal' => 'Início/Fim',
            'document' => 'Documento',
        ],
        'step_description_placeholder' => 'Adicionar notas sobre esta etapa…',
        'lane_label_placeholder'       => 'ex. RH / TI / Fornecedor',
        'group_label_placeholder'      => 'ex. Fase de resolução',
        'lane_hint'                    => 'Arraste o cabeçalho esquerdo da raia para reordenar. Arraste a borda inferior para redimensionar. Solte uma etapa na faixa para atribuí-la a esta raia.',
    ],

    'export_modal' => [
        'title'  => 'Exportar — Fluxograma Mermaid',
        'hint'   => 'Cole este código em qualquer editor Markdown compatível com Mermaid (GitHub, GitLab, Notion, Confluence, Obsidian…). As raias se tornam blocos <code>subgraph</code>; o layout automático substitui suas posições.',
        'copy'   => 'Copiar',
        'copied' => 'Copiado ✓',
        'close'  => 'Fechar',
    ],

    'toast' => [
        'no_process_open' => 'Abra ou crie um processo primeiro',
        'saved'           => 'Salvo',
        'save_failed'     => 'Falha ao salvar',
    ],
];
