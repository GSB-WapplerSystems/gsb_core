export function getRangeText (range) {
  return Array.from(range.getItems()).reduce((rangeText, node) => {
    if (!(node.is('text') || node.is('textProxy'))) {
      return rangeText
    }

    return rangeText + node.data
  }, '')
}

export function findAttributeRange (position, attributeName, value, model) {
  return model.createRange(_findBound(position, attributeName, value, true, model), _findBound(position, attributeName, value, false, model))
}
/**
 * Walks forward or backward (depends on the `lookBack` flag), node by node, as long as they have the same attribute value
 * and returns a position just before or after (depends on the `lookBack` flag) the last matched node.
 *
 * @param position The start position.
 * @param attributeName The attribute name.
 * @param value The attribute value.
 * @param lookBack Whether the walk direction is forward (`false`) or backward (`true`).
 * @returns The position just before the last matched node.
 */
function _findBound (position, attributeName, value, lookBack, model) {
  let node = position.textNode || (lookBack ? position.nodeBefore : position.nodeAfter)
  let lastNode = null
  while (node && node.getAttribute(attributeName) === value) {
    lastNode = node
    node = lookBack ? node.previousSibling : node.nextSibling
  }
  return lastNode ? model.createPositionAt(lastNode, lookBack ? 'before' : 'after') : position
}
