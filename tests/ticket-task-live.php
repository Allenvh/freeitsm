<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
/** Native task editor inside a real ticket. Disposable local data; no external services. */
require __DIR__.'/support/ticket-workspace-fixture.php';
require __DIR__.'/support/ticket-workspace-browser.php';
$fixture=null;$restores=[];$exit=1;
try {
    $fixture=ticketWorkspaceFixture();extract($fixture);
    foreach (['ticket_workspace_mode'=>'selected','tasks_forms_enabled'=>'1','tasks_checklists_enabled'=>'1'] as $key=>$value) {
        $restores[]=ticketWorkspaceSetting($conn,$key,$value);
    }
    $conn->prepare('UPDATE tickets SET is_workspace=1 WHERE id=?')->execute([$ticket]);
    $conn->prepare("INSERT INTO analyst_modules (analyst_id,module_key,access_level) VALUES (?,'forms','full')")->execute([$analysts['full']['id']]);
    $child=$insert('tasks',['title'=>'ZZ Embedded child','parent_task_id'=>$task,'tenant_id'=>$tenant,'created_by_id'=>$analysts['full']['id']]);
    $checklist=$insert('task_checklists',['task_id'=>$task,'title'=>'ZZ Embedded checklist','closure_mode'=>'block']);
    $checkItem=$insert('task_checklist_items',['task_checklist_id'=>$checklist,'title'=>'ZZ Confirm result','is_mandatory'=>1]);
    $form=$insert('forms',['title'=>'ZZ Embedded form','created_by'=>$analysts['full']['id'],'submission_actions'=>'{"submitted":[],"approved":[],"rejected":[]}']);
    $field=$insert('form_fields',['form_id'=>$form,'field_type'=>'text','label'=>'ZZ Outcome','is_required'=>1]);
    $formLink=$insert('task_forms',['task_id'=>$task,'form_id'=>$form,'title'=>'ZZ Embedded form','is_required'=>1,'requirement_mode'=>'block']);
    $fixture+=compact('child','checkItem','formLink','field');
    $exit=ticketWorkspaceBrowser([],['url'=>$url.'/auth/login.php','verify'=>function(string $ws)use($fixture):array {
        extract($fixture);
        $credentials=json_encode(['username'=>$analysts['full']['username'],'password'=>$analysts['full']['password']]);
        cdpEvaluate($ws,"fetch('login.php',{method:'POST',body:new URLSearchParams($credentials)}).then(r=>r.ok?'ok':'failed')",20);
        cdpEvaluate($ws,'',10,['method'=>'Page.navigate','params'=>['url'=>$url.'/tickets/?ticket_id='.$ticket]]);
        usleep(500000);
        return json_decode(cdpEvaluate($ws,<<<JS
(async()=>{
 const checks=[],check=(label,pass,detail)=>checks.push({label,pass:!!pass,detail});
 window.__taskChecks=checks;
 const wait=async(fn)=>{for(let i=0;i<200;i++){if(await fn())return true;await new Promise(r=>setTimeout(r,100));}return false;};
 await wait(()=>document.querySelector('.tw-grid')&&ticketPendingWrites===0);
 let reportUrl='';
 await TicketWorkspace.openTask($ticket,$task);
 await wait(()=>document.querySelector('.tw-task-frame')?.contentDocument?.getElementById('detailTitle'));
 const frame=document.querySelector('.tw-task-frame');const doc=frame?.contentDocument;
 check('native task fields initialise inside the ticket',!!doc?.getElementById('detailTitle'));
 await wait(()=>doc?.querySelector('.task-embed-open')&&document.querySelector('.tw-task-controls')?.hidden);
 const external=doc.querySelector('.task-embed-open');
 check('compact task actions share the native header',external?.closest('.detail-panel-actions')&&new URL(external.href).searchParams.get('task')==='$task'&&external.target==='_blank'&&external.getBoundingClientRect().height<=32&&document.querySelector('.tw-task-controls').hidden);
 const nativeTaskOpen=frame.contentWindow.open;frame.contentWindow.open=url=>{reportUrl=url;};
 doc.querySelector('[onclick="openTaskCaseReport()"]').click();frame.contentWindow.open=nativeTaskOpen;
 check('native task report remains available in the embedded editor',new URL(reportUrl,location.href).pathname.endsWith('/tasks/case-report.php')&&new URL(reportUrl,location.href).searchParams.get('id')==='$task');
 if(doc?.getElementById('detailTitle')){
     const title=doc.getElementById('detailTitle');title.value='ZZ Workspace saved in ticket';title.dispatchEvent(new Event('change',{bubbles:true}));
     const saved=await wait(async()=>{
       const data=await fetch('../api/tasks/get.php?id=$task').then(r=>r.json());
       return data.task?.title==='ZZ Workspace saved in ticket';
     });
     check('embedded editor saves through the native task API',saved);
     TicketWorkspace.activate('conversation');TicketWorkspace.activate('work');
     check('switching registers retains the native editor',document.querySelector('.tw-task-frame')===frame);
     await loadTicketById($ticket);
     check('refreshing the same ticket retains the native task document',document.querySelector('.tw-task-frame')===frame);
     const start=doc.getElementById('detailWorkStart');
     if(start){
       start.value='2026-11-02T10:00';start.dispatchEvent(new Event('change',{bubbles:true}));
       await wait(async()=>{const data=await fetch('../api/tasks/get.php?id=$task').then(r=>r.json());return data.task?.work_start_datetime==='2026-11-02 10:00:00';});
       frame.contentWindow.showConfirm=async()=>false;
       check('a saved scheduled-work field releases its draft',await TicketWorkspace.canLeave());
     }
     const comment=doc.getElementById('newCommentInput');
     check('native comment editor is available',!!comment);
     if(comment){comment.value='ZZ unsent comment';comment.dispatchEvent(new Event('input',{bubbles:true}));frame.contentWindow.showConfirm=async()=>false;
       check('native unsent task comment prevents unconfirmed navigation',!await TicketWorkspace.canLeave());
       await frame.contentWindow.closeDetailPanel();
       check('native Close also preserves an unconfirmed task draft',document.querySelector('.tw-task-frame')===frame&&comment.value==='ZZ unsent comment');
       comment.value='';comment.dispatchEvent(new Event('input',{bubbles:true}));}
 }

 await wait(()=>doc.querySelector('#taskDocuments input[type="file"]'));
 check('native document attachment controls are available',!!doc.querySelector('#taskDocuments input[type="file"]'));
 await wait(()=>doc.querySelector('#taskChecklistSection .chk-step input[type="checkbox"]'));
 const step=doc.querySelector('#taskChecklistSection .chk-step input[type="checkbox"]');
 if(step){step.click();await wait(()=>doc.querySelector('#taskChecklistSection .chk-step.done'));}
 check('native checklist completion saves in the embedded editor',!!doc.querySelector('#taskChecklistSection .chk-step.done'));
 await wait(()=>doc.querySelector('.tf-fill'));
 doc.querySelector('.tf-fill')?.click();
 await wait(()=>doc.querySelector('.tf-frame')?.contentDocument?.querySelector('[name="field_$field"]'));
 const formFrame=doc.querySelector('.tf-frame');const answer=formFrame?.contentDocument?.querySelector('[name="field_$field"]');
 check('required form opens in the nested native editor',!!answer);
 if(answer){
   await new Promise(r=>setTimeout(r,1000));
   answer.value='ZZ Native form answer';answer.dispatchEvent(new Event('input',{bubbles:true}));
   frame.contentWindow.showConfirm=async()=>false;
   check('nested native form draft protects leaving the ticket',!await TicketWorkspace.canLeave());
   formFrame.contentDocument.querySelector('#fillForm').requestSubmit();
   await wait(()=>doc.querySelector('.tf-card.done'));
   check('native form submission is retained on the task',!!doc.querySelector('.tf-card.done'));
 }
 // The same task editor opens an accessible descendant, then returns to its root.
 await frame.contentWindow.openDetailPanel($child);
 await wait(()=>doc.querySelector('#detailTitle')?.value==='ZZ Embedded child');
 check('subtask navigation stays in the same embedded editor',doc.querySelector('#detailTitle')?.value==='ZZ Embedded child'&&document.querySelector('.tw-task-frame')===frame);
 check('Open separately follows the displayed subtask',new URL(doc.querySelector('.task-embed-open').href).searchParams.get('task')==='$child');
 await frame.contentWindow.openDetailPanel($task);
 await wait(()=>doc.querySelector('#detailTitle')?.value==='ZZ Workspace saved in ticket');
 check('returning to the linked root restores its native fields',doc.querySelector('#detailTitle')?.value==='ZZ Workspace saved in ticket',doc.querySelector('#detailTitle')?.value);
 // A delayed failure for an old task must not invalidate a newer editor.
 await new Promise(r=>setTimeout(r,600));
 await frame.contentWindow.openDetailPanel($task);
 const nativeFetch=frame.contentWindow.fetch;let resolveOld;
 frame.contentWindow.fetch=(input,init)=>String(input).includes('get.php?id=$child&')
   ?new Promise(resolve=>{resolveOld=resolve;}):nativeFetch(input,init);
 const oldOpen=frame.contentWindow.openDetailPanel($child);
 await wait(()=>resolveOld);
 await frame.contentWindow.openDetailPanel($task);
 resolveOld(new Response(JSON.stringify({success:false}),{headers:{'Content-Type':'application/json'}}));
 await oldOpen;frame.contentWindow.fetch=nativeFetch;
 check('a stale task failure cannot invalidate a newer editor',!doc.getElementById('detailPanel').hidden&&!doc.getElementById('taskEmbedError'));
 await TicketWorkspace.requestPersonal(false);
 check('layout opt-out disposes the embedded editor after consent',!document.querySelector('.tw-task-frame')&&!document.querySelector('.tw-grid'));
 return JSON.stringify(checks);
})().catch(error=>JSON.stringify([...window.__taskChecks,{label:'native embedded task completes',pass:false,detail:String(error)}]))
JS,120),true);
    }]);
} finally {
    if($fixture){
        $conn=$fixture['conn'];$task=$fixture['task'];
        $conn->prepare('DELETE d FROM form_submission_data d JOIN form_submissions s ON s.id=d.submission_id WHERE s.task_id=?')->execute([$task]);
        $conn->prepare('DELETE FROM form_submissions WHERE task_id=?')->execute([$task]);
        $conn->prepare('DELETE FROM task_checklist_item_events WHERE task_id=?')->execute([$task]);
    }
    foreach(array_reverse($restores)as$restore)$restore();
    if($fixture)($fixture['cleanup'])();
}
exit($exit);
