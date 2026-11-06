<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/** The reading pane's one-row Links strip (fitLinksStrip in inbox.js); PHP + Chrome only, no database.
 * The real displayEmail() draws the strip at several pane widths: Link to… must
 * stay on the pills' row, names truncate, the overflow folds into "+N" and
 * unfolds again, and the workspace's Links tab keeps wrapping.
 * Control: without the reading-pane.css rules the narrow-pane checks fail.
 */
require __DIR__ . '/support/ticket-workspace-browser.php';
require __DIR__ . '/support/ticket-workspace-page.php';
$html = ticketWorkspacePage();
$html .= <<<'HTML'
<script>
document.addEventListener=originalAdd;
const checks=[];
const check=(label,pass,detail)=>checks.push({label,pass:!!pass,detail});
const frames=()=>new Promise(r=>requestAnimationFrame(()=>requestAnimationFrame(()=>setTimeout(r,50))));
(async()=>{
try {
    for(const name of ['loadAiSummary','loadTicketChecklists','loadCorrespondenceThread','loadNotes',
        'loadTicketAttachments','loadCmdbObjects','loadTicketDomains','loadTicketProjects','loadTicketAssets',
        'loadTicketTasks','loadTimeEntries','loadSlaState','loadTicketCustomFields','startPresence']) window[name]=()=>{};
    departments=[{id:1,name:'ZZ Service desk'}];analysts=[];ticketStatuses=[{id:1,name:'Open'}];
    ticketPriorities=[];ticketTeams=[];ticketClassification={};
    const related=[1,2,3,4,5,6].map(n=>({ticket_id:200+n,link_id:n,ticket_number:'ZZ-20'+n,status:'Open',subject:'ZZ related '+n}));
    const ticket={id:10,ticket_id:101,ticket_number:'ZZ-101',subject:'ZZ Links',status:'Open',department_id:1,
        from_address:'requester@example.invalid',from_name:'ZZ Requester',to_recipients:'desk@example.invalid',
        received_datetime:'2026-01-01 09:00:00',body_content:'ZZ body',body_type:'text',
        linked_tickets:{related},problems:[],changes:[]};
    const pane=document.getElementById('readingPane');
    const strip=()=>pane.querySelector('.links-strip');
    const more=()=>strip().querySelector('.links-strip-more');
    const pills=()=>[...strip().querySelectorAll('.pm-ticket-badge')];
    const shown=()=>pills().filter(p=>getComputedStyle(p).display!=='none');
    const label=()=>strip().querySelector('.problem-strip-label');
    const button=()=>strip().querySelector('.problem-link-btn');
    const sameRow=(a,b)=>Math.abs(a.getBoundingClientRect().top+a.offsetHeight/2-(b.getBoundingClientRect().top+b.offsetHeight/2))<6;
    window.TICKET_WORKSPACE={mode:'off',personal:true,ready:true,can_write:true};

    pane.style.width='1400px';
    displayEmail(ticket,[]);await frames();
    check('wide: every pill shows',shown().length===6,shown().length);
    check('wide: no +N chip',more().hidden);
    check('wide: Link to… on the label row',sameRow(label(),button()));

    pane.style.width='620px';await frames();
    const folded=6-shown().length;
    check('narrow: Link to… stays on the label row',sameRow(label(),button()));
    check('narrow: some pills fold away',folded>0&&shown().length>=1,folded);
    check('narrow: chip counts the folded pills',!more().hidden&&more().textContent==='+'+folded,more().textContent);
    check('narrow: chip on the same row',sameRow(label(),more()));
    check('narrow: the strip is one line',strip().offsetHeight<60,strip().offsetHeight);

    more().click();await frames();
    check('+N unfolds every pill',shown().length===6);
    check('unfolded chip reads Less',!more().hidden&&!more().textContent.startsWith('+'),more().textContent);
    more().click();await frames();
    check('Less folds them again',shown().length===6-folded);

    pane.style.width='1400px';await frames();
    check('widening unfolds without a click',shown().length===6&&more().hidden);

    // One very long name in a narrow pane truncates instead of folding.
    const long={...ticket,linked_tickets:{},problems:[{id:1,problem_number:'PRB-0001',status:'Investigating a very long problem name that will never fit in the strip'}]};
    pane.style.width='620px';displayEmail(long,[]);await frames();
    const name=strip().querySelector('.pill-text');
    check('a lone long name truncates',name.scrollWidth>name.clientWidth,[name.scrollWidth,name.clientWidth]);
    check('a lone pill never folds',shown().length===1&&more().hidden);
    check('the truncated name has a hover title',(name.title||'').includes('PRB-0001'),name.title);
    check('a long name keeps Link to… on the row',sameRow(label(),button()));

    // A picker takes its own row under the strip.
    openStripPicker('ZZ search');
    const host=document.getElementById('stripPickerHost');
    check('an open picker sits under the pills',host.getBoundingClientRect().top>=button().getBoundingClientRect().bottom-1);

    // The workspace's Links tab is untouched: the pill box is display: contents.
    window.TICKET_WORKSPACE={mode:'all',personal:true,ready:true,can_write:true};
    displayEmail(ticket,[]);await frames();
    check('workspace: no +N chip',!strip().querySelector('.links-strip-more'));
    check('workspace: the pills box takes no layout',getComputedStyle(strip().querySelector('.links-strip-pills')).display==='contents');
} catch(error) { check('renderer completes without errors',false,String(error)); }
document.getElementById('result').textContent=JSON.stringify(checks);
})();
</script></body></html>
HTML;
exit(ticketWorkspaceBrowser(['index.html' => $html]));
