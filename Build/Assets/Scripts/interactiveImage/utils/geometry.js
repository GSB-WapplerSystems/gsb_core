export const Geometry = {
    getPolygonCentroid(points) {
        const pts = points;
        const n = pts.length;
        if (n === 0) return null;
        if (n < 3) {
            let sx = 0,
                sy = 0;
            for (const p of pts) {
                sx += p.x;
                sy += p.y;
            }
            return {
                x: sx / n,
                y: sy / n
            };
        }
        let area2 = 0,
            cx = 0,
            cy = 0;
        for (let i = 0; i < n; i++) {
            const j = (i + 1) % n;
            const cross = pts[i].x * pts[j].y - pts[j].x * pts[i].y;
            area2 += cross;
            cx += (pts[i].x + pts[j].x) * cross;
            cy += (pts[i].y + pts[j].y) * cross;
        }
        if (area2 === 0) {
            let sx = 0,
                sy = 0;
            for (const p of pts) {
                sx += p.x;
                sy += p.y;
            }
            return {
                x: sx / n,
                y: sy / n
            };
        }
        return {
            x: cx / (3 * area2),
            y: cy / (3 * area2)
        };
    },

    pointInPolygon(x, y, points) {
        const n = points.length;
        if (n < 3) return false;
        let inside = false;
        for (let i = 0, j = n - 1; i < n; j = i++) {
            const xi = points[i].x,
                yi = points[i].y;
            const xj = points[j].x,
                yj = points[j].y;
            const intersect = ((yi > y) !== (yj > y)) && (x < (xj - xi) * (y - yi) / ((yj - yi) || 1e-12) + xi);
            if (intersect) inside = !inside;
        }
        return inside;
    },

    nearestPoint(x, y, points, threshold = Infinity) {
        let idx = -1,
            best = Infinity;
        for (let i = 0; i < points.length; i++) {
            const dx = points[i].x - x,
                dy = points[i].y - y;
            const d2 = dx * dx + dy * dy;
            if (d2 < best) {
                best = d2;
                idx = i;
            }
        }
        if (idx === -1) return -1;
        return Math.sqrt(best) <= threshold ? idx : -1;
    }
};
