import os
import json

brain_dir = '/home/fidi/.gemini/antigravity-ide/brain/'
transcripts = []

for root, dirs, files in os.walk(brain_dir):
    for f in files:
        if f == 'transcript_full.jsonl':
            transcripts.append(os.path.join(root, f))

print(f"Found {len(transcripts)} transcript files.")

reconstructed = {}

for t_path in sorted(transcripts, key=os.path.getmtime):
    with open(t_path, 'r', encoding='utf-8', errors='ignore') as f:
        for line in f:
            try:
                data = json.loads(line)
                
                # Check tool_calls in steps
                tool_calls = data.get('tool_calls', [])
                for call in tool_calls:
                    name = call.get('name')
                    args = call.get('args', {})
                    if name == 'write_to_file':
                        tf = args.get('TargetFile', '')
                        if tf.startswith('/var/www/ncs_intranet/app/') and 'CodeContent' in args:
                            reconstructed[tf] = args['CodeContent']
                
                # Check step content for view_file outputs
                content = data.get('content', '')
                marker = 'File Path: `file:///var/www/ncs_intranet/app/'
                if marker in content:
                    start_idx = content.find(marker) + len('File Path: `file://')
                    end_idx = content.find('`', start_idx)
                    tf = content[start_idx:end_idx].split('#')[0]
                    
                    if 'Showing lines' in content and 'The following code has been modified' in content:
                        lines = content.splitlines()
                        code_lines = []
                        in_code = False
                        for l in lines:
                            if 'The following code has been modified' in l:
                                in_code = True
                                continue
                            if in_code:
                                parts = l.split(':', 1)
                                if len(parts) == 2 and parts[0].strip().isdigit():
                                    code_lines.append(parts[1][1:] if len(parts[1]) > 0 and parts[1][0] == ' ' else parts[1])
                        if code_lines:
                            if tf not in reconstructed or len(code_lines) >= len(reconstructed[tf].splitlines()):
                                reconstructed[tf] = '\n'.join(code_lines)
            except Exception as e:
                pass

print(f"Total app/ files found in transcripts: {len(reconstructed)}")
restored_count = 0
for k in sorted(reconstructed.keys()):
    os.makedirs(os.path.dirname(k), exist_ok=True)
    with open(k, 'w', encoding='utf-8') as out:
        out.write(reconstructed[k])
    restored_count += 1
    print(f" Restored: {k}")

print(f"Restoration complete! {restored_count} files restored.")
