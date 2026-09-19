// Read-only production verification: login is the only permitted POST.
const {chromium}=require('/tmp/ncs-asset-verification/node_modules/playwright');
const fs=require('fs'),path=require('path'),assert=require('node:assert/strict');
(async()=>{
 const browser=await chromium.launch({executablePath:'/usr/bin/google-chrome',headless:true,args:['--no-sandbox']});
 const checks=[],errors=[],events=[];const base='https://ncsintranet.atenimedia.com';
 try {
 const rows=fs.readFileSync(path.resolve(__dirname,'../../Credentials.csv'),'utf8').split('\n').map(x=>x.trim().split(','));
 async function login(role){
  const row=rows.find(r=>r[0]===role);assert(row,'Credential row exists');const context=await browser.newContext({viewport:{width:1440,height:1050}});
  await context.route('**/api/v1/**',route=>{const r=route.request();if(!['GET','OPTIONS'].includes(r.method())&&!r.url().endsWith('/auth/login'))return route.abort();return route.continue()});
  const page=await context.newPage();page.on('pageerror',e=>errors.push(e.message));page.on('response',r=>{if(r.url().includes('/expenses'))events.push({path:new URL(r.url()).pathname,status:r.status()})});
  await page.goto(base+'/login');await page.locator('#email').fill(row[3]);await page.locator('#password').fill(row[4]);const response=page.waitForResponse(r=>r.url().endsWith('/auth/login'));await page.getByRole('button',{name:/Sign In/}).click();assert.equal((await response).status(),200);await page.waitForURL('**/dashboard');return page;
 }
 async function shot(page,name){await page.locator('.app-preloader').waitFor({state:'hidden'});await page.evaluate(()=>window.scrollTo(0,0));await page.waitForTimeout(200);await page.screenshot({path:path.join(__dirname,name+'.png'),fullPage:true});}
 const page=await login('accountant');await page.getByRole('heading',{name:'Expense Management',exact:true}).waitFor();await shot(page,'01-dashboard');checks.push('Real accountant dashboard displays expense card');
 await page.goto(base+'/expenses');await page.getByText('No expenses match these filters.',{exact:true}).waitFor();assert.equal(await page.getByRole('link',{name:'Manage Categories',exact:true}).count(),0);await shot(page,'02-register');checks.push('Real empty expense register loads; no accountant category controls');
 await page.goto(base+'/expenses/new');await page.getByText('Recorded by Stella Akello',{exact:true}).waitFor();await page.getByText(/No active expense categories/).waitFor();assert(await page.getByRole('button',{name:'Record Expense',exact:true}).isDisabled());const today=new Intl.DateTimeFormat('en-CA',{timeZone:'Africa/Nairobi',year:'numeric',month:'2-digit',day:'2-digit'}).format(new Date());assert.equal(await page.getByLabel('Expense date',{exact:true}).inputValue(),today);await shot(page,'03-entry');checks.push('Entry reads real recorder, current EAT date and requires administrator category setup');
 await page.reload();await page.getByText('Recorded by Stella Akello',{exact:true}).waitFor();checks.push('Direct expense entry link survives reload');
 await page.goto(base+'/expenses/categories');await page.waitForURL(url=>!url.pathname.includes('/expenses/categories'));checks.push('Accountant is redirected away from admin category page');
 await page.setViewportSize({width:390,height:844});await page.goto(base+'/expenses/new');await page.getByLabel('Expense date',{exact:true}).waitFor();assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth));await shot(page,'04-mobile');checks.push('Mobile entry fits viewport');
 const adminRole=rows.some(r=>r[0]==='super_admin')?'super_admin':'admin';const admin=await login(adminRole);await admin.goto(base+'/expenses/categories');await admin.getByRole('button',{name:'Save Category',exact:true}).waitFor();await admin.getByText('No categories yet. Create the first category above.',{exact:true}).waitFor();await shot(admin,'05-admin-categories');checks.push('Real administrator can open category management');
 assert.deepEqual(errors,[]);assert(events.length>0&&events.every(e=>e.status===200),'Expense API reads succeed');
 fs.writeFileSync(path.join(__dirname,'results.json'),JSON.stringify({site:base,checks,events,errors,expense_writes:0,category_writes:0},null,2));console.log(JSON.stringify({checks,errors},null,2));
 }finally{await browser.close()}
})().catch(e=>{console.error(e);process.exitCode=1});
