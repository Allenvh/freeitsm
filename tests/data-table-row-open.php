<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/** The shared table's rowOpen link (GH #219, the Tasks table); PHP + Chrome only, no database.
 * An open link heads every row: it is a real link (href), a plain click calls onOpen and
 * stays on the page, a Ctrl- or middle-click is left to the browser, the in-place editors
 * still edit rather than open, and the header and empty state make room for it.
 * Control: without rowOpen there is no extra column at all.
 */
require __DIR__.'/support/ticket-workspace-browser.php';
$script = file_get_contents(dirname(__DIR__).'/assets/js/data-table.js');
$html = '<!doctype html><html><body><input id="dtSearch"><button id="dtCsvBtn">CSV</button><span id="dtCount"></span><table id="dtTable"><thead id="dtHead"></thead><tbody id="dtBody"></tbody></table><pre id="result"></pre><script>window.t=k=>k;window.fetch=async()=>({json:async()=>({success:true,preferences:{}})});</script><script>'.$script.'</script>'.<<<'HTML'
<script>
const checks=[],check=(label,pass,detail)=>checks.push({label,pass:!!pass,detail});
const rows=[{id:7,title:'ZZ First'},{id:9,title:'ZZ Second'}];
const opened=[];
const cols=[{key:'title',label:'Title',type:'string',defaultVisible:true,defaultOrder:0,editable:{kind:'text'}}];
(async()=>{
 const table=createDataTable({manual:true,columns:cols,prefKey:'t1',load:async()=>rows,onSaveCell:async()=>{},
   rowOpen:{href:r=>'../index.php?task='+r.id,onOpen:r=>opened.push(r.id),label:'Open'}});
 await table.start();
 const links=[...document.querySelectorAll('#dtBody a.dt-open')];
 check('every row has an open link',links.length===2,links.length);
 check('it is a real link to the task',links[0].getAttribute('href')==='../index.php?task=7');
 check('it has an accessible name',links[0].getAttribute('aria-label')==='Open');
 check('the header makes room for it',document.querySelectorAll('#dtHead th').length===2&&!!document.querySelector('#dtHead th.dt-open-col'));
 const plain=new MouseEvent('click',{bubbles:true,cancelable:true,button:0});
 links[1].dispatchEvent(plain);
 check('a plain click opens in place',opened.join()==='9'&&plain.defaultPrevented);
 const ctrl=new MouseEvent('click',{bubbles:true,cancelable:true,button:0,ctrlKey:true});
 links[0].dispatchEvent(ctrl);
 check('a Ctrl-click is left to the browser',opened.length===1&&!ctrl.defaultPrevented);
 document.querySelector('#dtBody input.dt-edit').click();
 check('clicking the title edits, it does not open',opened.length===1);
 const arrow=()=>document.querySelector('#dtHead th[data-col-key] .dt-sort-arrow').textContent;
 const before=arrow();
 document.querySelector('#dtHead th[data-col-key] .dt-th-content').click();
 check('sorting still works beside it',arrow()!==before,before+' -> '+arrow());
 const empty=createDataTable({manual:true,columns:cols,prefKey:'t2',load:async()=>[],rowOpen:{href:r=>'#'}});
 await empty.start();
 check('the empty state spans the open column too',document.querySelector('#dtBody td.dt-empty')?.getAttribute('colspan')==='2');
 const plainTable=createDataTable({manual:true,columns:cols,prefKey:'t3',load:async()=>rows});
 await plainTable.start();
 check('a table without rowOpen is unchanged',!document.querySelector('#dtBody a.dt-open')&&document.querySelectorAll('#dtHead th').length===1);
})().catch(e=>check('table executes: '+e.message,false)).finally(()=>document.getElementById('result').textContent=JSON.stringify(checks));
</script></body></html>
HTML;
exit(ticketWorkspaceBrowser(['index.html'=>$html]));
