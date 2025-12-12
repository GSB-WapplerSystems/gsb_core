// SPDX-FileCopyrightText: 2025 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

// hotspotHelper.js

function calculateSimpleCentroid(points) {
    let sumX = 0;
    let sumY = 0;
    const pointCount = points.length;

    for (const point of points) {
        sumX += point.x;
        sumY += point.y;
    }

    return {
        x: sumX / pointCount,
        y: sumY / pointCount
    };
}

function calculatePolygonCentroidUsingShoelace(points) {
    let doubleArea = 0;
    let centroidX = 0;
    let centroidY = 0;
    const pointCount = points.length;

    for (let i = 0; i < pointCount; i++) {
        const nextIndex = (i + 1) % pointCount;
        const currentPoint = points[i];
        const nextPoint = points[nextIndex];

        const crossProduct = currentPoint.x * nextPoint.y - nextPoint.x * currentPoint.y;
        doubleArea += crossProduct;
        centroidX += (currentPoint.x + nextPoint.x) * crossProduct;
        centroidY += (currentPoint.y + nextPoint.y) * crossProduct;
    }

    if (doubleArea === 0) return null;

    return {
        x: centroidX / (3 * doubleArea),
        y: centroidY / (3 * doubleArea)
    };
}

function isRayIntersectingEdge(x, y, currentPoint, previousPoint, currentY, previousY) {
    const isPointBetweenYCoordinates = (currentY > y) !== (previousY > y);
    if (!isPointBetweenYCoordinates) return false;

    const deltaX = previousPoint.x - currentPoint.x;
    const deltaY = previousY - currentY;
    const denominator = deltaY || 1e-12;

    const intersectionX = deltaX * (y - currentY) / denominator + currentPoint.x;
    return x < intersectionX;
}

export const hotspotHelper = {
    getPolygonCentroid(points) {
        if (points.length === 0) return null;

        if (points.length < 3) {
            return calculateSimpleCentroid(points);
        }

        const centroid = calculatePolygonCentroidUsingShoelace(points);
        return centroid || calculateSimpleCentroid(points);
    },

    pointInPolygon(x, y, points) {
        if (points.length < 3) return false;

        let isInside = false;
        const pointCount = points.length;

        for (let i = 0, previousIndex = pointCount - 1; i < pointCount; previousIndex = i++) {
            const currentPoint = points[i];
            const previousPoint = points[previousIndex];
            const currentY = currentPoint.y;
            const previousY = previousPoint.y;

            const isRayIntersecting = isRayIntersectingEdge(
                x,
                y,
                currentPoint,
                previousPoint,
                currentY,
                previousY
            );

            if (isRayIntersecting) {
                isInside = !isInside;
            }
        }

        return isInside;
    },

    nearestPoint(x, y, points, threshold = Infinity) {
        let nearestPointIndex = -1;
        let minimumDistanceSquared = Infinity;

        for (let i = 0; i < points.length; i++) {
            const point = points[i];
            const deltaX = point.x - x;
            const deltaY = point.y - y;
            const distanceSquared = deltaX * deltaX + deltaY * deltaY;

            if (distanceSquared < minimumDistanceSquared) {
                minimumDistanceSquared = distanceSquared;
                nearestPointIndex = i;
            }
        }

        if (nearestPointIndex === -1) return -1;

        const minimumDistance = Math.sqrt(minimumDistanceSquared);
        return minimumDistance <= threshold ? nearestPointIndex : -1;
    },

    normalizedToCanvas(points, drawPolygon) {
        if (!drawPolygon) return points;

        const { dx, dy, dw, dh } = drawPolygon;
        return points.map(({ u, v }) => ({
            x: dx + u * dw,
            y: dy + v * dh
        }));
    },

    resolveColor(cssVariableName) {
        const rootElement = document.documentElement;
        const computedStyle = getComputedStyle(rootElement);
        const colorValue = computedStyle.getPropertyValue(cssVariableName).trim();
        return colorValue;
    }
};
