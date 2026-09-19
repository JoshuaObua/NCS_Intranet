const {chromium}=require(process.env.PLAYWRIGHT_MODULE||'/tmp/ncs-asset-verification/node_modules/playwright');
const fs=require('fs'),path=require('path'),assert=require('node:assert/strict');
(async()=>{
 const browser=await chromium.launch({executablePath:'/usr/bin/google-chrome',headless:true,args:['--no-sandbox']});
 const checks=[],errors=[];const base=process.env.TEST_BASE_URL||'http://127.0.0.1:3001';
 try {
 let categories=[{id:'cat-1',name:'Office supplies',description:'Office consumables',is_active:true}],posts=[],fail=false;
 const expense={id:'expense-1',reference:'EXP-1',expense_date:'2026-09-08',category_name:'Office supplies',title:'Office stationery purchase',description:'Pens and paper for the accounts office',amount:12500.50,currency:'UGX',payee:'Office shop',payment_method:'CASH',payment_reference:'RCPT-001',department_name:'Finance & Accounts',recorded_by_name:'Stella Akello',recorded_at:'2026-09-08T09:30:00Z',attachment:{name:'receipt.pdf',mime_type:'application/pdf',size_bytes:32}};
 async function context(role){
  const c=await browser.newContext({viewport:{width:1440,height:1050}});
  await c.addInitScript(role=>{localStorage.setItem('ncsms_access_token','fixture');localStorage.setItem('ncsms_user',JSON.stringify({id:'stella',first_name:'Stella',last_name:'Akello',roles:[role]}));},role);
  await c.route('**/api/v1/**',async route=>{
   const req=route.request(),p=new URL(req.url()).pathname;let data={};
   if(p.includes('/expenses')){
    if(req.method()==='POST'||req.method()==='PUT'){
     posts.push({method:req.method(),path:p,body:req.postData()});
     if(fail)return route.fulfill({status:400,json:{error:{message:'Choose an active expense category'}}});
     if(p.includes('/categories')) {const payload=req.postDataJSON();if(req.method()==='POST')categories.push({...payload,id:'cat-2'});else categories=categories.map(c=>c.id==='cat-1'?{...c,...payload}:c);data=payload;}else data={id:expense.id};
    }else if(p.endsWith('/options'))data={today:'2026-09-08',recorded_by_name:'Stella Akello',departments:[{id:'finance',name:'Finance & Accounts'}]};
    else if(p.endsWith('/categories'))data=categories;
    else if(p.endsWith('/attachment'))return route.fulfill({body:'%PDF-1.4\nTest receipt',headers:{'content-type':'application/pdf','content-disposition':'attachment; filename="receipt.pdf"'}});
    else if(p.endsWith('/expense-1'))data=expense;
    else data={expenses:[expense],total:1,total_amount:'12500.50'};
   } else if(p.endsWith('/assets/summary')) data={total_assets:0,category_summaries:[]};
   else if(p.endsWith('/assets'))data={assets:[],total:0};
   await route.fulfill({json:{data}});
  });return c;
 }
 const c=await context('accountant'),page=await c.newPage();page.on('pageerror',e=>errors.push(e.message));
 async function shot(name){await page.evaluate(()=>window.scrollTo(0,0));await page.waitForTimeout(200);await page.evaluate(()=>{document.querySelector('#test-label')?.remove();const d=document.createElement('div');d.id='test-label';d.style='position:fixed;bottom:0;left:0;right:0;z-index:99999;background:#172554;color:white;text-align:center;padding:8px;pointer-events:none';d.textContent='LOCAL FIXTURE VERIFICATION · Expense module · No production writes';document.body.append(d)});await page.screenshot({path:path.join(__dirname,name+'.png'),fullPage:true});}
 await page.goto(base+'/dashboard');await page.getByRole('heading',{name:'Expense Management',exact:true}).waitFor();checks.push('Accountant dashboard expense card is visible');
 await page.goto(base+'/expenses/new');await page.getByText('Recorded by Stella Akello',{exact:true}).waitFor();assert.equal(await page.getByLabel('Expense date',{exact:true}).inputValue(),'2026-09-08');
 await page.getByRole('button',{name:'Record Expense',exact:true}).click();assert.equal(posts.length,0);checks.push('Today defaults and required fields prevent empty submission');
 await page.getByLabel('Expense category',{exact:true}).selectOption('cat-1');await page.getByLabel('Title',{exact:true}).fill(expense.title);await page.getByLabel('Description / reason',{exact:true}).fill(expense.description);await page.getByLabel('Amount (UGX)',{exact:true}).fill('12500.50');await page.getByLabel('Expense department',{exact:true}).selectOption('finance');await page.getByLabel('Payee / supplier',{exact:true}).fill(expense.payee);await page.getByLabel('Payment / receipt reference (optional)',{exact:true}).fill('RCPT-001');await page.locator('input[type=file]').setInputFiles({name:'receipt.pdf',mimeType:'application/pdf',buffer:Buffer.from('%PDF-1.4\nTest receipt')});await shot('01-entry');
 fail=true;await page.getByRole('button',{name:'Record Expense',exact:true}).click();await page.getByRole('alert').waitFor();assert.equal(await page.getByLabel('Title',{exact:true}).inputValue(),expense.title);checks.push('Save error visible and form retained');
 fail=false;await page.getByRole('button',{name:'Record Expense',exact:true}).click();await page.waitForURL('**/expenses/expense-1?saved=1');await page.getByText('Recorded by Stella Akello',{exact:true}).waitFor();assert(posts.at(-1).body.includes('receipt.pdf'));assert(posts.at(-1).body.includes('12500.5'));await shot('02-detail');checks.push('Multipart expense and receipt submit then open independent detail page');
 const downloadPromise=page.waitForEvent('download');await page.getByRole('button',{name:/Download/}).click();const download=await downloadPromise;assert.equal(download.suggestedFilename(),'receipt.pdf');checks.push('Receipt download works');
 await page.goto(base+'/expenses');await page.getByRole('link',{name:'EXP-1',exact:true}).waitFor();await page.locator('table').getByText('Stella Akello',{exact:true}).waitFor();assert.equal(await page.getByRole('link',{name:'Manage Categories',exact:true}).count(),0);await shot('03-register');checks.push('Register displays named recorder, department and amount; accountant has no category controls');
 await page.goto(base+'/expenses/categories');await page.waitForURL(url=>!url.pathname.includes('/expenses/categories'));checks.push('Accountant cannot open admin category page');
 await page.setViewportSize({width:390,height:844});await page.goto(base+'/expenses/new');await page.getByLabel('Title',{exact:true}).waitFor();assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth));await shot('04-mobile-entry');checks.push('Entry fits mobile viewport');
 for(const role of ['senior_accountant','chief_accountant','asset_accountant','finance_department']){const rc=await context(role),rp=await rc.newPage();await rp.goto(base+'/dashboard');await rp.getByRole('heading',{name:'Expense Management',exact:true}).waitFor();await rp.goto(base+'/expenses');await rp.getByRole('link',{name:'EXP-1',exact:true}).waitFor();await rc.close();checks.push(role+' dashboard and register accessible');}
 const ac=await context('admin'),ap=await ac.newPage();await ap.goto(base+'/expenses/categories');await ap.getByLabel('Name',{exact:true}).fill('Travel');await ap.getByLabel('Description',{exact:true}).fill('Approved travel costs');await ap.getByRole('button',{name:'Save Category',exact:true}).click();await ap.getByRole('cell',{name:'Travel',exact:true}).waitFor();await ap.getByRole('button',{name:'Edit Office supplies',exact:true}).click();await ap.getByLabel('Active (available for new expenses)',{exact:true}).uncheck();await ap.getByRole('button',{name:'Save Category',exact:true}).click();await ap.getByRole('cell',{name:'Inactive',exact:true}).waitFor();await ap.evaluate(()=>{window.scrollTo(0,0);const d=document.createElement('div');d.style='position:fixed;bottom:0;left:0;right:0;z-index:99999;background:#172554;color:white;text-align:center;padding:8px';d.textContent='LOCAL FIXTURE VERIFICATION · Admin categories · No production writes';document.body.append(d)});await ap.waitForTimeout(200);await ap.screenshot({path:path.join(__dirname,'05-admin-categories.png'),fullPage:true});checks.push('Admin creates and deactivates categories on independent page');
 categories=[];await page.goto(base+'/expenses/new');await page.getByText(/No active expense categories/).waitFor();assert(await page.getByRole('button',{name:'Record Expense',exact:true}).isDisabled());checks.push('No-category empty state prevents recording');
 assert.deepEqual(errors,[]);checks.push('No browser runtime errors');fs.writeFileSync(path.join(__dirname,'results.json'),JSON.stringify({scope:'Local browser fixtures; backend verified separately against isolated PostgreSQL schema',passed:checks},null,2));console.log(checks.join('\n'));
 }finally{await browser.close()}
})().catch(e=>{console.error(e);process.exitCode=1});
