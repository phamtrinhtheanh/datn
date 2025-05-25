import { computed } from 'vue';

export function useSmartPagination(links: any[]) {
    return computed(() => {
        if (!links || links.length <= 2) return [];

        // Remove first and last links (Previous/Next buttons)
        const pageLinks = links.slice(1, -1);
        const totalPages = pageLinks.length;
        if (totalPages <= 4) {
            // Show all pages if 4 or fewer
            return pageLinks.map((link, index) => ({
                ...link,
                page: index + 1,
                type: 'page'
            }));
        }

        // Find current page
        const currentPageIndex = pageLinks.findIndex(link => link.active);
        const currentPage = currentPageIndex + 1;
        const pages = [];

        // Always show first page
        pages.push({
            ...pageLinks[0],
            page: 1,
            type: 'page'
        });

        // Show left ellipsis if needed
        if (currentPage > 3) {
            pages.push({
                url: null,
                label: '...',
                active: false,
                page: null,
                type: 'ellipsis'
            });
        }

        // Show current, one before and one after (if in range)
        for (let i = currentPage - 1; i <= currentPage + 1; i++) {
            if (i > 1 && i < totalPages) {
                pages.push({
                    ...pageLinks[i - 1],
                    page: i,
                    type: 'page'
                });
            }
        }

        // Show right ellipsis if needed
        if (currentPage < totalPages - 2) {
            pages.push({
                url: null,
                label: '...',
                active: false,
                page: null,
                type: 'ellipsis'
            });
        }

        // Always show last page
        pages.push({
            ...pageLinks[totalPages - 1],
            page: totalPages,
            type: 'page'
        });

        return pages;
    });
} 