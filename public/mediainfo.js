const fs = require('fs');
const { execFileSync } = require('child_process');
const ff = 'C:/Users/Orange/AppData/Local/Temp/opencode/pw/node_modules/ffmpeg-static/ffmpeg.exe';
for (const f of ['assets/videos/wedding-hero.mp4','assets/videos/wedding-hero-mobile.mp4']) {
  const b = fs.readFileSync(f);
  console.log(f, 'size', b.length, 'moov@', b.indexOf('moov'), 'mdat@', b.indexOf('mdat'));
  try {
    execFileSync(ff, ['-hide_banner','-i',f], {stdio:['ignore','ignore','pipe']});
  } catch (e) {
    console.log(e.stderr.toString().split('\n').filter(l => /Duration|Stream/.test(l)).join('\n'));
  }
}
