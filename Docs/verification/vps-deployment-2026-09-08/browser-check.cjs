// Read-only acceptance against production. Only login is allowed to POST.
const {chromium}=require('/tmp/ncs-asset-verification/node_modules/playwright');
const fs=require('fs'),path=require('path'),assert=require('node:assert/strict');
(async()=>{
 const browser=await chromium.launch({executablePath:'/usr/bin/google-chrome',headless:true,args:['--no-sandbox']});
 const events=[],errors=[],checks=[];
 try{
 const context=await browser.newContext({viewport:{width:1440,height:1000}});
 await context.route('**/api/v1/**',route=>{const r=route.request();if(!['GET','OPTIONS'].includes(r.method())&&!r.url().endsWith('/auth/login')){events.push({blocked:new URL(r.url()).pathname,method:r.method()});return route.abort();}return route.continue()});
 const page=await context.newPage();page.on('pageerror',e=>errors.push(e.message));page.on('response',r=>{if(r.url().includes('/api/v1/'))events.push({path:new URL(r.url()).pathname,status:r.status(),method:r.request().method()})});
 const cells=fs.readFileSync(path.resolve(__dirname,'../../Credentials.csv'),'utf8').split('\n').find(line=>line.startsWith('accountant,')).split(',');
 const base='https://ncsintranet.atenimedia.com';
 await page.goto(base+'/login');await page.locator('#email').fill(cells[3]);await page.locator('#password').fill(cells[4]);
 const login=page.waitForResponse(r=>r.url().endsWith('/auth/login')&&r.request().method()==='POST');await page.getByRole('button',{name:/Sign In/}).click();const loginResponse=await login;assert.equal(loginResponse.status(),200,'Accountant login');await page.waitForURL('**/dashboard');checks.push('Real accountant login');
 const shot=async name=>{await page.locator('.app-preloader').waitFor({state:'hidden'});await page.locator('h1').last().scrollIntoViewIfNeeded().catch(()=>{});await page.screenshot({path:path.join(__dirname,name+'.png')})};
 await page.locator('.main-sidebar a[href="/fixed-assets"]').waitFor();await shot('01-accountant-dashboard');await page.locator('.main-sidebar a[href="/fixed-assets"]').click();await page.getByText('Showing 100 of 297 records').waitFor();checks.push('Register reads 297 real VPS assets');await shot('02-register');
 await page.getByRole('link',{name:/New Fixed Asset/}).click();await page.waitForURL('**/fixed-assets/new');await page.getByLabel('Asset Number',{exact:true}).waitFor();await shot('03-new-asset-page');checks.push('New asset independent page');await page.getByRole('link',{name:'Cancel',exact:true}).click();
 await page.locator('.asset-actions a').first().waitFor();await page.getByRole('link',{name:'Adjust',exact:true}).first().click();await page.getByLabel('New Adjusted Valuation (UGX)').waitFor();await page.reload();await page.getByLabel('New Adjusted Valuation (UGX)').waitFor();await shot('04-revaluation-page');checks.push('Revaluation deep link survives reload and loads real asset');await page.getByRole('link',{name:'Cancel',exact:true}).click();
 await page.getByRole('link',{name:'Verify',exact:true}).first().click();await page.getByLabel('Verification Status').waitFor();await shot('05-verification-page');checks.push('Verification independent page loads real asset');await page.getByRole('link',{name:'Cancel',exact:true}).click();
 await page.getByRole('link',{name:/Run Monthly Depreciation/}).click();await page.getByLabel('Period').waitFor();await shot('06-depreciation-page');checks.push('Depreciation independent page');await page.getByRole('link',{name:'Cancel',exact:true}).click();
 await page.getByRole('button',{name:'Next page',exact:true}).click();await page.getByText('101–200 of 297 assets').waitFor();await page.getByRole('button',{name:'Next page',exact:true}).click();await page.getByText('Showing 97 of 297 records').waitFor();checks.push('All three asset pages accessible');
 await page.setViewportSize({width:390,height:844});await page.goto(base+'/fixed-assets/new');await page.getByLabel('Asset Number',{exact:true}).waitFor();await shot('07-mobile-new-asset');assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth));checks.push('Mobile new-asset page fits viewport');
 assert.equal(errors.length,0,'Browser runtime errors');fs.writeFileSync(path.join(__dirname,'browser-results.json'),JSON.stringify({site:base,checks,asset_writes:0,events,errors},null,2));console.log(JSON.stringify({passed:checks,errors},null,2));
 }finally{await browser.close()}
})().catch(e=>{console.error(e);process.exitCode=1});
