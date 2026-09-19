const {chromium}=require('/tmp/ncs-asset-verification/node_modules/playwright');
const fs=require('fs'),path=require('path'),assert=require('node:assert/strict');
(async()=>{
 const browser=await chromium.launch({executablePath:'/usr/bin/google-chrome',headless:true,args:['--no-sandbox']});
 try {
 const context=await browser.newContext({viewport:{width:1440,height:1000}});
 await context.addInitScript(()=>{localStorage.setItem('ncsms_access_token','fixture');localStorage.setItem('ncsms_user',JSON.stringify({first_name:'Test',last_name:'Accountant',roles:['accountant']}));});
 const asset={id:'asset-test',asset_number:'M1007155',tag_number:'TEST-LAND',asset_description:'Test asset',category_segment3:'LAND',asset_units:1,fb_cost:100,adjusted_cost:100,net_book_value:100,verification_status:'MISSING'};
 const posts=[],checks=[];let failPost=false,delayPost=false;
 await context.route('**/api/v1/**',async route=>{
 const req=route.request(),url=new URL(req.url());let data={};
 if(req.method()==='POST'){
  posts.push({endpoint:url.pathname,payload:req.postDataJSON()});
  if(delayPost)await new Promise(r=>setTimeout(r,500));
  return route.fulfill({status:failPost?422:200,json:failPost?{error:{message:'Fixture validation error'}}:{data:{assets_processed:1}}});
 }
 if(url.pathname.endsWith('/assets/summary'))data={total_assets:1,total_fb_cost:100,total_adjusted_cost:100,total_net_book_value:100,category_summaries:[]};
 else if(url.pathname.endsWith('/assets'))data={assets:[asset],total:1};
 else if(url.pathname.endsWith('/assets/asset-test'))data={asset,logs:[]};
 else if(url.pathname.includes('/assets/'))return route.fulfill({status:404,json:{error:{message:'Asset not found'}}});
 await route.fulfill({json:{data}});
 });
 const page=await context.newPage();const base='http://127.0.0.1:3001';
 const screenshot=async name=>{await page.locator('h1').last().scrollIntoViewIfNeeded();await page.evaluate(()=>{const x=document.createElement('div');x.style='position:fixed;bottom:0;left:0;right:0;z-index:99999;background:#172554;color:white;text-align:center;padding:8px;pointer-events:none';x.textContent='LOCAL FIXTURE TEST · Independent asset pages · No database writes';document.body.append(x)});await page.screenshot({path:path.join(__dirname,name+'.png')});};
 await page.goto(base+'/dashboard');await page.getByRole('link',{name:/New Fixed Asset/}).click();checks.push('Accountant dashboard opens independent new-asset page');await page.waitForURL('**/fixed-assets/new');
 await page.getByRole('button',{name:'Create Asset',exact:true}).click();assert.equal(posts.length,0);checks.push('Required new-asset fields block empty submission');
 await page.getByLabel('Asset Number',{exact:true}).fill('NEW-1');await page.getByLabel('Tag Number',{exact:true}).fill('TAG-1');await page.getByLabel('Description',{exact:true}).fill('New test asset');await page.getByLabel('FB Cost',{exact:true}).fill('123.45');await page.getByLabel('Adjusted Cost',{exact:true}).fill('123.45');await screenshot('01-new-asset-page');
 failPost=true;await page.getByRole('button',{name:'Create Asset',exact:true}).click();await page.getByRole('alert').waitFor();assert.equal(await page.getByLabel('Asset Number',{exact:true}).inputValue(),'NEW-1');checks.push('Nested API error visible and entered data retained');
 failPost=false;delayPost=true;await page.getByRole('button',{name:'Create Asset',exact:true}).click();assert(await page.getByRole('button',{name:'Saving…',exact:true}).isDisabled());await page.waitForURL('**/fixed-assets?completed=new');assert.equal(posts.length,2);assert.equal(posts[1].payload.fb_cost,123.45);checks.push('Create submits decimal values once and returns to register');delayPost=false;
 await page.getByRole('link',{name:'Adjust',exact:true}).click();await page.waitForURL('**/asset-test/revalue');await page.getByLabel('New Adjusted Valuation (UGX)').waitFor();await page.reload();await page.getByLabel('New Adjusted Valuation (UGX)').waitFor();assert.equal(await page.getByLabel('New Adjusted Valuation (UGX)').inputValue(),'100');await screenshot('02-revaluation-page');await page.getByLabel('New Adjusted Valuation (UGX)').fill('150.25');await page.getByRole('button',{name:'Save Revaluation'}).click();await page.waitForURL('**/fixed-assets?completed=revalue');assert.deepEqual(posts.at(-1),{endpoint:'/api/v1/assets/revalue',payload:{asset_id:'asset-test',new_cost:150.25,notes:''}});checks.push('Revaluation direct refresh and submission');
 await page.getByRole('link',{name:'Verify',exact:true}).click();await page.getByLabel('Verification Status').waitFor();assert.equal(await page.getByLabel('Verification Status').inputValue(),'MISSING');await screenshot('03-verification-page');await page.getByLabel('Verification Status').selectOption('VERIFIED');await page.getByLabel('Notes',{exact:true}).fill('Checked');await page.getByRole('button',{name:'Save Verification'}).click();await page.waitForURL('**/fixed-assets?completed=verify');assert.deepEqual(posts.at(-1).payload,{asset_id:'asset-test',status:'VERIFIED',notes:'Checked'});checks.push('Verification loads existing status and posts asset ID');
 await page.getByRole('link',{name:/Run Monthly Depreciation/}).click();await page.waitForURL('**/fixed-assets/depreciation');await page.getByLabel('Period').fill('2026-09');await screenshot('04-depreciation-page');await page.getByRole('button',{name:'Run Depreciation',exact:true}).click();await page.waitForURL('**/fixed-assets?completed=depreciation');assert.deepEqual(posts.at(-1),{endpoint:'/api/v1/assets/depreciate',payload:{period:'2026-09'}});checks.push('Depreciation separate page and period payload');
 const count=posts.length;await page.goto(base+'/fixed-assets/new');await page.getByRole('link',{name:'Cancel',exact:true}).click();await page.waitForURL('**/fixed-assets');assert.equal(posts.length,count);checks.push('Cancel returns without posting');
 await page.goto(base+'/fixed-assets/unknown/revalue');await page.getByRole('alert').waitFor();assert.equal(await page.locator('form').count(),0);checks.push('Invalid asset cannot submit');
 await page.setViewportSize({width:390,height:844});await page.goto(base+'/fixed-assets/new');await page.getByLabel('Asset Number',{exact:true}).waitFor();await screenshot('05-new-asset-mobile');assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth));checks.push('Mobile page fits viewport');
 fs.writeFileSync(path.join(__dirname,'results.json'),JSON.stringify({scope:'Fixture browser tests; all API writes intercepted',passed:checks,posts},null,2));console.log(checks.join('\n'));
 }finally{await browser.close()}
})().catch(e=>{console.error(e);process.exitCode=1});
