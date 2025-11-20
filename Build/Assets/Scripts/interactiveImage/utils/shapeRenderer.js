export const ShapeRenderer = {
    drawGrid(ctx, w, h, step = 40, color = '#ffffff10'){
      ctx.save();
      ctx.strokeStyle = color; ctx.lineWidth = 1;
      for (let x = 0; x < w; x += step){ ctx.beginPath(); ctx.moveTo(x,0); ctx.lineTo(x,h); ctx.stroke(); }
      for (let y = 0; y < h; y += step){ ctx.beginPath(); ctx.moveTo(0,y); ctx.lineTo(w,y); ctx.stroke(); }
      ctx.restore();
    },
    drawPolygon(ctx, points, { stroke = '#fff', fill = null, lineWidth = 2, close = true } = {}){
      if (!points || points.length < 2) return;
      ctx.save();
      ctx.beginPath();
      ctx.moveTo(points[0].x, points[0].y);
      for (let i = 1; i < points.length; i++) ctx.lineTo(points[i].x, points[i].y);
      if (close) ctx.closePath();
      if (fill){ ctx.fillStyle = fill; ctx.fill(); }
      ctx.lineWidth = lineWidth; ctx.strokeStyle = stroke; ctx.lineJoin = 'round'; ctx.lineCap = 'round'; ctx.stroke();
      ctx.restore();
    },
    drawCenterDot(ctx, c, { radius = 5, color = '#fff' } = {}){
      if (!c) return;
      ctx.save();
      ctx.fillStyle = color;
      ctx.beginPath(); ctx.arc(c.x, c.y, radius, 0, Math.PI*2); ctx.fill();
      ctx.restore();
    }
  };
