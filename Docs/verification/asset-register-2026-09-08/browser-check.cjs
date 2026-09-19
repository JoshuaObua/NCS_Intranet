// Local fixture UI verification only. Never posts to a deployed API.
const { chromium } = require('/tmp/ncs-asset-verification/node_modules/playwright');
const fs = require('fs'); const path = require('path');
const dir = __dirname, assets = JSON.parse(fs.readFileSync(path.join(dir,'workbook-fixture.json')));
(async()=>{
 const browser=await chromium.launch({executablePath:'/usr/bin/google-chrome',headless:true,args:['--no-sandbox']});
 const context=await browser.newContext({viewport:{width:1440,height:1000}});
 const user={id:'fixture-accountant',first_name:'Fixture',last_name:'Accountant',roles:['accountant']};
 await context.addInitScript(user=>{localStorage.setItem('ncsms_access_token','local-fixture-only');localStorage.setItem('ncsms_user',JSON.stringify(user));},user);
 const groups={};for(const a of assets){const key=[a.category_segment1,a.category_segment3,a.category_segment4].join('|');const g=groups[key]??={...a,asset_units:0,total_fb_cost:0,total_adjusted_cost:0,total_net_book_value:0};g.asset_units+=a.asset_units;g.total_fb_cost+=a.fb_cost;g.total_adjusted_cost+=a.adjusted_cost;g.total_net_book_value+=a.net_book_value;}
 const summary={total_assets:297,total_fb_cost:31015914535,total_adjusted_cost:32175914535,total_net_book_value:32175914535,verified_assets:0,discrepancy_assets:0,category_summaries:Object.values(groups)};
 let failSummary=false;const requests=[];
 await context.route('**/api/v1/**',async route=>{
  const req=route.request(),u=new URL(req.url());requests.push({path:u.pathname,method:req.method()});
  if(req.method()!=='GET'){await route.abort();return;}
  if(failSummary&&u.pathname.endsWith('/assets/summary')){await route.fulfill({status:503,json:{message:'Fixture summary unavailable'}});return;}
  let data={};
  if(u.pathname.endsWith('/auth/me'))data=user;
  else if(u.pathname.endsWith('/assets/summary'))data=summary;
  else if(u.pathname.endsWith('/assets')){const cat=u.searchParams.get('category'),search=(u.searchParams.get('search')||'').toLowerCase();const rows=assets.filter(a=>(!cat||a.category_segment3===cat)&&(!search||[a.asset_number,a.tag_number,a.asset_description].join(' ').toLowerCase().includes(search)));const offset=Number(u.searchParams.get('offset')||0);data={assets:rows.slice(offset,offset+Number(u.searchParams.get('limit')||50)),total:rows.length};}
  await route.fulfill({json:{data}});
 });
 const page=await context.newPage(),results=[];const check=(name,ok)=>{results.push({name,pass:!!ok});if(!ok)console.error('FAIL',name)};
 const shot=async name=>{await page.evaluate(()=>{let x=document.getElementById('fixture-label');if(!x){x=document.createElement('div');x.id='fixture-label';x.style='position:fixed;bottom:0;left:0;right:0;z-index:99999;pointer-events:none;background:#172554;color:white;padding:8px;text-align:center;font:14px sans-serif';x.textContent='LOCAL UI VERIFICATION · Workbook fixture data · Not live database evidence';document.body.append(x)}});await page.screenshot({path:path.join(dir,name+'.png')});};
 await page.goto('http://127.0.0.1:3001/fixed-assets');await page.getByText('Showing 100 of 297 records').waitFor();
 check('Accountant navigation link visible',await page.getByRole('link',{name:/Fixed Asset Register/}).isVisible());
 await shot('01-register-desktop');
 await page.getByRole('button',{name:'Next page',exact:true}).click();await page.getByText('101–200 of 297 assets').waitFor();
 await page.getByRole('button',{name:'Next page',exact:true}).click();await page.getByText('Showing 97 of 297 records').waitFor();check('Final page reachable',true);await shot('02-final-page');
 await page.locator('select').first().selectOption('LAND');await page.getByText('Showing 8 of 8 records').waitFor();check('Category resets pagination',true);
 await page.getByPlaceholder('Search Tag, Code, Name...').fill('M1007155');await page.getByText('Showing 1 of 1 records').waitFor();check('Search and category combined',true);
 await page.getByRole('button',{name:/New Fixed Asset/}).click();await page.getByText('Register New Fixed Asset',{exact:true}).waitFor();await shot('03-new-asset');await page.getByRole('button',{name:'Cancel',exact:true}).click();
 await page.getByRole('button',{name:'Adjust',exact:true}).click();await shot('04-adjustment-dialog');await page.getByRole('button',{name:'Cancel',exact:true}).click();
 await page.getByRole('button',{name:'Value Adjustments',exact:true}).click();await shot('05-adjustments-missing-log');
 await page.getByRole('button',{name:/Dynamic Pivot Engine/}).click();await shot('06-pivot');check('Pivot rows rendered',await page.locator('tbody tr').count()===Object.values(groups).length);
 await page.getByRole('button',{name:/Run Monthly Depreciation/}).click();await shot('07-depreciation-dialog');await page.getByRole('button',{name:'Cancel',exact:true}).click();
 await page.setViewportSize({width:390,height:844});await page.getByRole('button',{name:/Asset Register/}).click();await page.locator('h1').last().scrollIntoViewIfNeeded();await shot('08-mobile-register');
 check('Mobile page fits viewport',await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth));
 await page.setViewportSize({width:1440,height:1000});failSummary=true;await page.reload();await page.getByText('Fixture summary unavailable').waitFor();check('Failed summary does not show hardcoded total',!(await page.locator('h3').allTextContents()).some(s=>s.includes('31,015,914,535')));await shot('09-summary-failure');
 fs.writeFileSync(path.join(dir,'browser-results.json'),JSON.stringify({scope:'Local browser, intercepted API using workbook fixture; no server writes',results,requests},null,2));
 await context.close();await browser.close();console.log(JSON.stringify(results,null,2));if(results.some(r=>!r.pass))process.exitCode=1;
})().catch(e=>{console.error(e);process.exit(1)});
