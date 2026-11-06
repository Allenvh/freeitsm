/**
 * FreeITSM SOP / Checklists: Template Search & Relevance Scoring Utility
 * 
 * Provides multi-token search and field-weighted relevance scoring across:
 * - title / label (exact > prefix > substring)
 * - keywords (exact > substring)
 * - category (exact > substring)
 * - description
 */
(function(root) {
    /**
     * Matches a template against a search (every word must appear somewhere) and scores it, title hits highest, then keywords, category and description.
     * @param {object} item  a template with title/label, category, keywords and description
     * @param {string} query  the search text
     * @return {{matched: boolean, score: number}}  an empty query matches everything with score 0
     */
    function scoreChecklistTemplate(item, query) {
        if (!query) return { matched: true, score: 0 };
        const q = String(query).toLowerCase().trim();
        if (!q) return { matched: true, score: 0 };

        const title = (item.title || item.label || '').toLowerCase();
        const category = (item.category || '').toLowerCase();
        const keywords = (item.keywords || '').toLowerCase();
        const description = (item.description || item.desc || '').toLowerCase();
        const haystack = `${title} ${category} ${keywords} ${description}`;

        const tokens = q.split(/\s+/).filter(Boolean);
        const allMatch = tokens.every(token => haystack.includes(token));
        if (!allMatch) return { matched: false, score: 0 };

        let score = 0;
        tokens.forEach(token => {
            if (title === token) score += 120;
            else if (title.startsWith(token)) score += 100;
            else if (title.includes(token)) score += 80;

            if (keywords.split(/[\s,]+/).includes(token)) score += 70;
            else if (keywords.includes(token)) score += 50;

            if (category === token) score += 40;
            else if (category.includes(token)) score += 30;

            if (description.includes(token)) score += 15;
        });

        return { matched: true, score };
    }

    if (typeof module !== 'undefined' && module.exports) {
        module.exports = { scoreChecklistTemplate };
    } else {
        root.scoreChecklistTemplate = scoreChecklistTemplate;
    }
})(typeof window !== 'undefined' ? window : this);
