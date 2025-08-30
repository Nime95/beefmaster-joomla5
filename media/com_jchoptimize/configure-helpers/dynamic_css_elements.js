/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 *  @package   jchoptimize/core
 *  @author    Samuel Marshall <samuel@jch-optimize.net>
 *  @copyright Copyright (c) 2024 Samuel Marshall / JCH Optimize
 *  @license   GNU/GPLv3, or later. See LICENSE file
 *
 *  If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

document.addEventListener('DOMContentLoaded', () => {
    console.info('Checking for Dynamic CSS Selectors. Please wait...');
    const observedSelectors = new Set();
    let finalSelectors = [];

    const simplify = (items) => {
        const sorted = [...items].sort();
        const result = [];

        for (let i = 0; i < sorted.length; i++) {
            const current = sorted[i];
            const prefix = current;

            if (!current.startsWith('.') && !current.startsWith('#') && !current.startsWith('[')) {
                result.push('\u200B' + current + '\u200B');
                continue;
            }

            const hasValidPrefix = result.some(existing => {
                const existingPrefix = existing;
                return prefix.startsWith(existingPrefix) && existingPrefix.length >= 4;
            });

            if (!hasValidPrefix) {
                if (prefix.length < 4) {
                    result.push(current + '\u200B');
                } else {
                    result.push(current);
                }
            }
        }

        return result;
    };

    const isInTargetArea = (el) => {
        const rect = el.getBoundingClientRect();
        return rect.top >= 0 && rect.top <= (window.innerHeight + 100);
    };

    const extractSelectors = (node) => {
        const results = new Set();

        if (node.nodeType !== Node.ELEMENT_NODE) return results;
        if (!isInTargetArea(node)) return results;

        const popularTags = ['a', 'div', 'span', 'img', 'p', 'h1', 'h2', 'ul', 'ol', 'li'];
        const tagName = node.tagName.toLowerCase();

        if (!popularTags.includes(tagName)) {
            results.add(node.tagName.toLowerCase());
        }

        if (node.id) results.add(`#${node.id}`);
        if (node.classList && node.classList.length > 0) {
            node.classList.forEach(cls => results.add(`.${cls}`));
        }

        for (const attr of node.attributes) {
            if (attr.name !== 'id' && attr.name !== 'class') {
                results.add(`[${attr.name}`);
            }
        }

        return results;
    };

    const extractAttributeChanges = (mutation) => {
        const results = new Set();
        const el = mutation.target;
        if (!isInTargetArea(el)) return results;

        const attrName = mutation.attributeName;
        const oldValue = mutation.oldValue || '';
        const newValue = el.getAttribute(attrName) || '';

        if (attrName === 'class') {
            const oldClasses = new Set(oldValue.split(/\s+/));
            const newClasses = new Set(newValue.split(/\s+/));
            for (const cls of newClasses) {
                if (cls && !oldClasses.has(cls)) {
                    results.add(`.${cls}`);
                }
            }
        } else if (attrName === 'id') {
            if (newValue && newValue !== oldValue) {
                results.add(`#${newValue}`);
            }
        } else {
            if (newValue && newValue !== oldValue) {
                results.add(`[${attrName}`);
            }
        }

        return results;
    };

    const observer = new MutationObserver((mutations) => {
        for (const mutation of mutations) {
            if (mutation.type === 'childList') {
                for (const node of mutation.addedNodes) {
                    const stack = [node];
                    while (stack.length > 0) {
                        const current = stack.pop();
                        extractSelectors(current).forEach(sel => observedSelectors.add(sel));
                        if (current.children) {
                            stack.push(...current.children);
                        }
                    }
                }
            } else if (mutation.type === 'attributes') {
                extractAttributeChanges(mutation).forEach(sel => observedSelectors.add(sel));
            }
        }
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeOldValue: true,
    });

    window.downloadDynamicSelectors = () => {
        const json = JSON.stringify({ merge: true, pro_dynamic_selectors: finalSelectors }, null, 2);
        const blob = new Blob([json], { type: 'application/json' });
        const url = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = url;
        a.download = 'dynamic_selectors.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    };

    // Wait until full load before printing/logging to avoid interfering with rendering
    window.addEventListener('load', () => {
        setTimeout(() => {
            observer.disconnect();
            finalSelectors = simplify(observedSelectors);
            finalSelectors.sort();

            if (finalSelectors.length > 0) {
                console.table(finalSelectors.map(selector => ({ 'CSS Dynamic Selectors': selector })));
                console.info('✅ Selector tracking complete.');
                console.info('▶ Run to download: downloadDynamicSelectors()');
            } else {
                console.info('ℹ️ No Dynamic CSS Selectors detected.');
            }
        }, 3000);
    });
});
