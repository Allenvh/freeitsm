<?php
/**
 * Português (Brasil) (pt-BR) — Watchtower module strings.
 * Missing keys fall back to lang/en/watchtower.php per-key.
 */
return [
    'title' => 'Watchtower',

    'nav' => [
        'dashboard' => 'Painel',
        'help'      => 'Ajuda',
    ],

    'dashboard' => [
        'heading'      => 'Visão geral de atenção',
        'refresh'      => 'Atualizar',
        'updated'      => 'Atualizado {time}',
    ],

    // Per-module card names shown in the card header (links to each module).
    'cards' => [
        'morning_checks' => 'Verificações matinais',
        'tickets'        => 'Chamados',
        'changes'        => 'Mudanças',
        'calendar'       => 'Calendário',
        'service_status' => 'Status do serviço',
        'contracts'      => 'Contratos',
        'knowledge'      => 'Conhecimento',
        'assets'         => 'Ativos',
        'tasks'          => 'Tarefas',
    ],

    // Morning Checks card.
    'mc' => [
        'metric_done' => 'Concluídas',
        'metric_ok'   => 'OK',
        'metric_warn' => 'Aviso',
        'metric_fail' => 'Falha',
        'not_started'      => 'Verificações não iniciadas hoje',
        'pending'          => '{count} verificações ainda pendentes',
        'failed'           => '{count} verificação(ões) com falha',
        'warnings'         => '{count} verificação(ões) com avisos',
        'all_passing'      => 'Todas as verificações concluídas e aprovadas',
    ],

    // Tickets card.
    'tickets' => [
        'metric_open'   => 'Abertos',
        'metric_new'    => 'Novos',
        'metric_active' => 'Ativos',
        'metric_hold'   => 'Em espera',
        'urgent_high'   => '<span class="wt-attention-bold">{count}</span> chamados de prioridade urgente/alta',
        'unassigned'    => '<span class="wt-attention-bold">{count}</span> chamados não atribuídos',
        'paused_one'    => '<span class="wt-attention-bold">{count}</span> chamado pausado há mais de {hours}h (relógio do SLA parado)',
        'paused_many'   => '<span class="wt-attention-bold">{count}</span> chamados pausados há mais de {hours}h (relógio do SLA parado)',
        'all_clear'     => 'Nenhum item urgente',
    ],

    // Changes card.
    'changes' => [
        'metric_next_7d' => 'Próx. 7d',
        'metric_active'  => 'Ativas',
        'metric_pending' => 'Pendentes',
        'awaiting'       => '<span class="wt-attention-bold">{count}</span> mudança(s) aguardando aprovação',
        'in_progress'    => '{count} mudança(s) em andamento agora',
        'scheduled'      => '{count} mudança(s) agendada(s) esta semana',
        'all_clear'      => 'Nenhuma mudança futura',
    ],

    // Calendar card.
    'calendar' => [
        'metric_today' => 'Hoje',
        'metric_week'  => 'Esta semana',
        'all_day'      => 'Dia inteiro',
        'no_events'    => 'Nenhum evento hoje',
    ],

    // Service Status card.
    'service' => [
        'all_operational' => 'Todos os sistemas operacionais',
        'active_incidents' => '<span class="wt-attention-bold">{count}</span> incidente(s) ativo(s)',
    ],

    // Contracts card.
    'contracts' => [
        'metric_30d'     => '30 dias',
        'metric_90d'     => '90 dias',
        'metric_notices' => 'Avisos',
        'expiring'       => '<span class="wt-attention-bold">{count}</span> contrato(s) expirando em até 30 dias',
        'notices'        => '<span class="wt-attention-bold">{count}</span> período(s) de aviso se aproximando',
        'all_clear'      => 'Nenhum contrato requer atenção',
    ],

    // Knowledge card.
    'knowledge' => [
        'overdue'         => '<span class="wt-attention-bold">{count}</span> artigo(s) com revisão atrasada',
        'published_week'  => 'Publicados esta semana',
        'up_to_date'      => 'Base de conhecimento atualizada',
    ],

    // Assets card.
    'assets' => [
        'metric_total'    => 'Total',
        'metric_offline'  => 'Offline',
        'metric_warranty' => 'Garantia',
        'warranty'        => '<span class="wt-attention-bold">{count}</span> ativo(s) com garantia expirada ou expirando em até {days} dias',
        'offline'         => '<span class="wt-attention-bold">{count}</span> ativo(s) sem contato há 7+ dias',
        'all_active'      => 'Todos os ativos ativos recentemente',
    ],

    // Tasks card.
    'tasks' => [
        'metric_todo'   => 'A fazer',
        'metric_active' => 'Ativas',
        'overdue'       => '<span class="wt-attention-bold">{count}</span> tarefa(s) atrasada(s)',
        'due_today'     => '<span class="wt-attention-bold">{count}</span> com vencimento hoje',
        'all_clear'     => 'Nenhuma tarefa atrasada',
    ],

    // Help guide.
    'help' => [
        'page_title'   => 'Guia do Watchtower',
        'sidebar_label' => 'Guia',
        'hero_title'   => 'Guia do Watchtower',
        'hero_subtitle' => 'Um painel de atenção unificado que mostra itens acionáveis de todos os módulos em um único relance.',

        'nav_overview'  => 'Visão geral',
        'nav_layout'    => 'O layout do painel',
        'nav_dots'      => 'Entendendo os pontos de status',
        'nav_cards'     => 'Cartões de módulo explicados',
        'nav_refresh'   => 'Atualização automática',
        'nav_tips'      => 'Dicas rápidas',

        // Section 1 — Overview
        's1_title' => 'Visão geral',
        's1_intro' => 'O Watchtower é o seu painel único para operações de TI. Em vez de abrir cada módulo individualmente para verificar itens urgentes, o Watchtower reúne as informações mais importantes de todos os módulos em um único painel. Em um relance você pode ver o que precisa de atenção, o que está funcionando sem problemas e onde concentrar o seu tempo.',
        's1_feat1_title' => 'Quadro de atenção',
        's1_feat1_desc'  => 'Veja o que precisa do seu foco em todos os módulos em um só lugar. Verificações matinais, chamados, mudanças, eventos de calendário, status do serviço, contratos, artigos de conhecimento e ativos são todos resumidos em uma única tela.',
        's1_feat2_title' => 'Status com código de cores',
        's1_feat2_desc'  => 'Cada cartão de módulo exibe um ponto de status verde, âmbar ou vermelho para triagem instantânea. Você consegue identificar em um relance quais áreas estão saudáveis, quais precisam de atenção e quais exigem ação imediata.',
        's1_feat3_title' => 'Atualização automática',
        's1_feat3_desc'  => 'O painel é atualizado automaticamente a cada 5 minutos, então as informações permanecem atuais sem nenhuma ação manual. Deixe o Watchtower aberto e ele se mantém atualizado em segundo plano.',
        's1_feat4_title' => 'Acesso direto',
        's1_feat4_desc'  => 'Vá diretamente para qualquer módulo a partir do seu cartão. Cada nome de módulo é um link clicável que leva você direto à área relevante, para que você possa agir sobre os problemas sem procurar a página certa.',

        // Section 2 — Dashboard layout
        's2_title' => 'O layout do painel',
        's2_p1' => 'O painel do Watchtower usa uma grade responsiva de 3 colunas de cartões de módulo. Em telas menores, a grade se adapta para 2 colunas ou uma única coluna, então funciona em qualquer dispositivo. Acima da grade fica a barra de título com um botão de atualizar e um carimbo de data/hora "Atualizado" mostrando quando os dados foram obtidos pela última vez.',
        's2_p2' => 'Cada cartão na grade segue uma estrutura consistente para que você possa examiná-los rapidamente:',
        's2_diagram_name'   => 'Nome do módulo',
        's2_diagram_open'   => 'ABERTOS',
        's2_diagram_active' => 'ATIVOS',
        's2_diagram_hold'   => 'EM ESPERA',
        's2_diagram_clear'  => 'Tudo certo — nenhum item urgente',
        's2_field_icon'    => '<strong>Ícone colorido</strong> &mdash; um pequeno ícone quadrado na cor do tema do módulo (verde-azulado para Verificações matinais, azul para Chamados, etc.) para que você possa identificar cada cartão instantaneamente.',
        's2_field_name'    => '<strong>Nome do módulo</strong> &mdash; um link clicável que navega diretamente para aquele módulo. Clique para ir direto e tomar uma ação.',
        's2_field_dot'     => '<strong>Ponto de status</strong> &mdash; um ponto verde, âmbar ou vermelho no canto superior direito mostrando o nível geral de urgência daquele módulo.',
        's2_field_metrics' => '<strong>Métricas-chave</strong> &mdash; números grandes resumindo as contagens mais importantes (por exemplo, chamados abertos, verificações concluídas, contratos expirando).',
        's2_field_attention' => '<strong>Itens de atenção</strong> &mdash; linhas de mensagem com código de cores destacando o que especificamente precisa da sua atenção dentro daquele módulo.',
        's2_tip' => 'O layout do cartão foi projetado para uma rápida análise visual, não para análise profunda. Use o Watchtower para identificar quais módulos precisam da sua atenção e, em seguida, clique para acessar o próprio módulo e ver todos os detalhes.',

        // Section 3 — Status dots
        's3_title' => 'Entendendo os pontos de status',
        's3_intro' => 'Cada cartão de módulo exibe um ponto de status em seu cabeçalho. Esse ponto fornece um indicador visual instantâneo de se aquela área das suas operações de TI precisa de atenção. A cor é determinada automaticamente com base nos dados retornados de cada módulo.',
        's3_green_label' => 'Verde',
        's3_green_desc'  => 'Está tudo bem. Nenhuma ação necessária. O módulo está em um estado saudável, sem problemas pendentes ou itens que requeiram atenção.',
        's3_green_examples' => '<strong>Exemplos:</strong> Todas as verificações matinais aprovadas, nenhum chamado urgente, todos os sistemas operacionais, nenhum contrato expirando em breve.',
        's3_amber_label' => 'Âmbar',
        's3_amber_desc'  => 'Algo precisa de atenção, mas não é crítico. Há itens que você deve revisar quando tiver oportunidade, mas nada está pegando fogo.',
        's3_amber_examples' => '<strong>Exemplos:</strong> Verificações com avisos, chamados não atribuídos, mudanças aguardando aprovação, contratos expirando em até 90 dias.',
        's3_red_label' => 'Vermelho',
        's3_red_desc'  => 'Itens urgentes exigem ação imediata. Algo falhou, está atrasado ou foi criticamente impactado e precisa ser resolvido imediatamente.',
        's3_red_examples' => '<strong>Exemplos:</strong> Verificações matinais não iniciadas ou com falha, chamados de prioridade urgente/alta, grandes interrupções de serviço, contratos expirando em até 30 dias.',
        's3_tip' => 'Pense nos pontos como um semáforo. Verde significa seguir com o seu dia, âmbar significa revisar quando possível e vermelho significa parar o que você está fazendo e investigar. O objetivo é manter todos os pontos verdes.',

        // Section 4 — Module cards explained
        's4_title' => 'Cartões de módulo explicados',
        's4_intro' => 'O Watchtower monitora oito módulos. Cada cartão é adaptado para mostrar as informações mais relevantes daquela área. Veja o que cada cartão exibe e o que aciona a cor do seu ponto de status.',
        's4_mc_title'    => 'Verificações matinais',
        's4_mc_desc'     => 'Mostra o progresso de conclusão (por exemplo, 8/10 concluídas) mais as contagens de resultados OK, Aviso e Falha. Os itens de atenção sinalizam quando as verificações não foram iniciadas ou quando alguma falhou.',
        's4_mc_triggers' => '<strong>Vermelho:</strong> Verificações não iniciadas hoje, ou alguma verificação com falha. <strong>Âmbar:</strong> Verificações incompletas ou avisos presentes. <strong>Verde:</strong> Todas as verificações concluídas e aprovadas.',
        's4_tk_title'    => 'Chamados',
        's4_tk_desc'     => 'Exibe a contagem total de abertos dividida em Novos, Ativos e Em espera. Os itens de atenção destacam chamados de prioridade urgente/alta e quaisquer que estejam não atribuídos.',
        's4_tk_triggers' => '<strong>Vermelho:</strong> Existem chamados de prioridade urgente ou alta. <strong>Âmbar:</strong> Chamados não atribuídos presentes. <strong>Verde:</strong> Nenhum item urgente ou chamado não atribuído.',
        's4_ch_title'    => 'Mudanças',
        's4_ch_desc'     => 'Mostra o número de mudanças agendadas nos próximos 7 dias, quantas estão atualmente em andamento e quantas estão aguardando aprovação. Os itens de atenção destacam mudanças não aprovadas e ativas.',
        's4_ch_triggers' => '<strong>Âmbar:</strong> Mudanças aguardando aprovação. <strong>Verde:</strong> Nenhuma mudança não aprovada.',
        's4_cal_title'    => 'Calendário',
        's4_cal_desc'     => 'Exibe o número de eventos de hoje e desta semana. Se houver eventos hoje, eles são listados com seus horários (ou "Dia inteiro" para eventos de dia inteiro).',
        's4_cal_triggers' => '<strong>Âmbar:</strong> Eventos agendados para hoje. <strong>Verde:</strong> Nenhum evento hoje.',
        's4_ss_title'    => 'Status do serviço',
        's4_ss_desc'     => 'Mostra a contagem de incidentes ativos e lista os serviços afetados com seus selos de nível de impacto (Interrupção grave, Interrupção parcial, Degradado, Manutenção). Quando tudo está saudável, aparece um banner verde "Todos os sistemas operacionais".',
        's4_ss_triggers' => '<strong>Vermelho:</strong> Interrupção grave ou parcial em qualquer serviço. <strong>Âmbar:</strong> Status degradado ou em manutenção. <strong>Verde:</strong> Todos os sistemas operacionais.',
        's4_ct_title'    => 'Contratos',
        's4_ct_desc'     => 'Exibe contratos expirando em até 30 dias, em até 90 dias e períodos de aviso se aproximando. Os itens de atenção alertam sobre expirações iminentes e prazos de aviso próximos.',
        's4_ct_triggers' => '<strong>Vermelho:</strong> Contratos expirando em até 30 dias. <strong>Âmbar:</strong> Contratos expirando em até 90 dias ou períodos de aviso se aproximando. <strong>Verde:</strong> Nenhum contrato requer atenção.',
        's4_kb_title'    => 'Conhecimento',
        's4_kb_desc'     => 'Mostra o número de artigos com revisão atrasada e lista os artigos publicados recentemente nesta semana. Quando não há revisões atrasadas e a base de conhecimento está atualizada, o cartão exibe uma mensagem de tudo certo.',
        's4_kb_triggers' => '<strong>Âmbar:</strong> Artigos com revisão atrasada. <strong>Verde:</strong> Base de conhecimento atualizada.',
        's4_as_title'    => 'Ativos',
        's4_as_desc'     => 'Exibe o número total de ativos monitorados e quantos não têm contato há 7 ou mais dias. Isso ajuda a identificar dispositivos que podem estar offline, desativados ou perdidos.',
        's4_as_triggers' => '<strong>Âmbar:</strong> Ativos sem contato há 7+ dias. <strong>Verde:</strong> Todos os ativos ativos recentemente.',

        // Section 5 — Auto-refresh
        's5_title' => 'Atualização automática e manual',
        's5_intro' => 'O Watchtower foi projetado para ser uma ferramenta de monitoramento passivo que você pode deixar aberta em uma aba do navegador ao longo do dia. O painel se mantém atualizado por meio de ciclos de atualização automática.',
        's5_step1' => '<strong>Atualização automática</strong> &mdash; o painel busca dados novos de todos os módulos a cada 5 minutos. Você não precisa recarregar a página nem clicar em nada; os cartões e pontos de status se atualizam silenciosamente em segundo plano.',
        's5_step2' => '<strong>Atualização manual</strong> &mdash; clique no botão <strong>Atualizar</strong> no canto superior direito para buscar os dados mais recentes imediatamente. O ícone do botão gira enquanto a requisição está em andamento, confirmando que novos dados estão sendo carregados.',
        's5_step3' => '<strong>Carimbo de atualização</strong> &mdash; ao lado do botão de atualizar, um carimbo de data/hora mostra a última vez que os dados foram obtidos (por exemplo, "Atualizado 09:15"). Isso informa exatamente quão atuais são as informações exibidas.',
        's5_tip' => 'Mantenha o Watchtower aberto em uma aba dedicada do navegador para monitoramento passivo. O ciclo de atualização de 5 minutos significa que você sempre tem uma visão quase em tempo real das suas operações de TI sem precisar verificar cada módulo manualmente.',

        // Section 6 — Quick tips
        's6_title' => 'Dicas rápidas',
        's6_tip1_title' => 'Comece o seu dia aqui',
        's6_tip1_desc'  => 'Abra o Watchtower logo cedo todas as manhãs para uma visão geral operacional rápida. Em segundos você pode ver se as verificações matinais foram concluídas, se algum chamado é urgente e se todos os serviços estão saudáveis.',
        's6_tip2_title' => 'Pontos vermelhos primeiro',
        's6_tip2_desc'  => 'Trate os pontos de status vermelhos antes de qualquer outra coisa. Eles indicam itens urgentes que precisam de atenção imediata &mdash; verificações com falha, chamados de alta prioridade ou interrupções de serviço que estão afetando ativamente os usuários.',
        's6_tip3_title' => 'Clique para entrar',
        's6_tip3_desc'  => 'Clique em qualquer nome de módulo em um cartão para navegar direto para aquele módulo. Não é preciso usar o menu principal ou a navegação em grade &mdash; o Watchtower funciona como um atalho direto para onde quer que a atenção seja necessária.',
        's6_tip4_title' => 'Clique em Atualizar para o mais recente',
        's6_tip4_desc'  => 'Embora o painel se atualize automaticamente a cada 5 minutos, você pode clicar no botão Atualizar sempre que quiser os dados mais recentes. Útil após resolver um problema para confirmar que o ponto de status mudou.',
        's6_tip5_title' => 'Use em reuniões de equipe',
        's6_tip5_desc'  => 'Projete o Watchtower em uma tela durante reuniões diárias ou reuniões de revisão operacional. Os pontos com código de cores facilitam discutir quais áreas precisam de atenção e atribuir responsabilidade pelos itens âmbar ou vermelhos.',
        's6_tip6_title' => 'Verde significa tudo certo',
        's6_tip6_desc'  => 'Quando todos os pontos do painel estão verdes, suas operações de TI estão em boa forma. Nenhum chamado urgente, nenhuma verificação com falha, nenhum contrato expirando e todos os serviços operacionais. Esse é o objetivo.',
    ],
];
