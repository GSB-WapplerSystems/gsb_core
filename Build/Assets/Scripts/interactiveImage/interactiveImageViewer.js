export class InteractiveImageViewer {
    /** @param {HTMLCanvasElement} canvas */
    constructor(canvas){
      this.canvas = canvas;
      this.ctx = canvas.getContext('2d');
      this.shapes = []; // [{points:[{x,y}], center:{x,y}}]
      this.lastSize = null;
      window.addEventListener('resize', () => this.handleResize());
      this.handleResize();
    }
    setShapes(shapes){
      this.shapes = Array.isArray(shapes) ? shapes.map(s => ({
        points: (s.points||[]).map(p => ({x:p.x, y:p.y})),
        center: s.center ? {x:s.center.x, y:s.center.y} : null,
      })) : [];
      this.draw();
    }
    handleResize(){

      // const rect = this.canvas.getBoundingClientRect();
      // const dpr = Math.max(1, window.devicePixelRatio || 1);
      // this.canvas.width = Math.round(rect.width * dpr);
      // this.canvas.height = Math.round(rect.height * dpr);
      // this.ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      // this.draw();
      // this.lastSize = { w: rect.width, h: rect.height };
    }
    draw(){
      const ctx = this.ctx;
      ctx.clearRect(0,0,this.canvas.width,this.canvas.height);
      // light grid
      ctx.save();
      ctx.strokeStyle = '#ffffff0f';
      for(let x=0;x<this.canvas.width;x+=40){ ctx.beginPath(); ctx.moveTo(x,0); ctx.lineTo(x,this.canvas.height); ctx.stroke(); }
      for(let y=0;y<this.canvas.height;y+=40){ ctx.beginPath(); ctx.moveTo(0,y); ctx.lineTo(this.canvas.width,y); ctx.stroke(); }
      ctx.restore();

      // draw each shape
      const palette = ['#f97316','#22c55e','#3b82f6','#eab308','#a855f7','#ef4444'];
      this.shapes.forEach((s, idx) => {
        const color = palette[idx % palette.length];
        if (s.points && s.points.length){
          ctx.save();
          ctx.beginPath();
          ctx.moveTo(s.points[0].x, s.points[0].y);
          for (let i=1;i<s.points.length;i++) ctx.lineTo(s.points[i].x, s.points[i].y);
          ctx.closePath();
          ctx.lineWidth = 2;
          ctx.strokeStyle = color;
          ctx.stroke();
          // points
          ctx.fillStyle = '#e5e7eb';
          s.points.forEach(p=>{ ctx.beginPath(); ctx.arc(p.x,p.y,3,0,Math.PI*2); ctx.fill(); });
          // center
          if (s.center){ ctx.beginPath(); ctx.fillStyle = '#22d3ee'; ctx.arc(s.center.x, s.center.y, 4, 0, Math.PI*2); ctx.fill(); }
          ctx.restore();
        }
      });
    }
  }
