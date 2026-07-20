document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(
        "[data-employee-search-form]"
    );

    /*
     * File ini hanya berjalan pada halaman dashboard
     * yang memiliki form pencarian employee.
     */
    if (!form) {
        return;
    }

    const input = form.querySelector(
        "[data-employee-search-input]"
    );

    const resetButton = form.querySelector(
        "[data-employee-search-reset]"
    );

    const results = document.querySelector(
        "[data-employee-search-results]"
    );

    const statistics = document.querySelector(
    "[data-dashboard-statistics]"
);

    const loadingIndicator = document.querySelector(
        "[data-employee-search-loading]"
    );

    const status = document.querySelector(
        "[data-employee-search-status]"
    );

    /*
     * Company filter.
     */
    const companyFilter = document.querySelector(
        "[data-company-filter]"
    );

    const companyCheckboxes = Array.from(
        document.querySelectorAll(
            "[data-company-filter-checkbox]"
        )
    );

    const companyFilterClear =
        companyFilter?.querySelector(
            "[data-company-filter-clear]"
        );

    const companyFilterLabel =
        companyFilter?.querySelector(
            "[data-company-filter-label]"
        );

    if (!input || !results) {
        return;
    }

    let debounceTimer = null;
    let activeRequest = null;

    const setLoading = (isLoading) => {
        results.setAttribute(
            "aria-busy",
            isLoading ? "true" : "false"
        );

        if (loadingIndicator) {
            loadingIndicator.hidden = !isLoading;

            loadingIndicator.style.display =
                isLoading ? "flex" : "none";
        }

        input.classList.toggle(
            "pr-11",
            isLoading
        );
    };

    /*
     * Memperbarui tulisan tombol filter.
     */
    const updateCompanyFilterLabel = () => {
        if (!companyFilterLabel) {
            return;
        }

        const selectedCheckboxes =
            companyCheckboxes.filter(
                (checkbox) => checkbox.checked
            );

        const selectedCount =
            selectedCheckboxes.length;

        if (selectedCount === 0) {
            companyFilterLabel.textContent =
                "Filter by Company";

            return;
        }

        if (selectedCount === 1) {
            const checkbox =
                selectedCheckboxes[0];

            const label = document.querySelector(
                `label[for="${CSS.escape(checkbox.id)}"]`
            );

            companyFilterLabel.textContent =
                label?.textContent.trim() ??
                "Company (1)";

            return;
        }

        companyFilterLabel.textContent =
            `Company (${selectedCount})`;
    };

    /*
     * Membuat URL berdasarkan search dan company
     * yang sedang dipilih.
     */
    const createSearchUrl = () => {
        const url = new URL(
            form.action,
            window.location.origin
        );

        const keyword = input.value.trim();

        if (keyword !== "") {
            url.searchParams.set(
                "search",
                keyword
            );
        }

        companyCheckboxes.forEach(
            (checkbox) => {
                if (!checkbox.checked) {
                    return;
                }

                url.searchParams.append(
                    "company[]",
                    checkbox.value
                );
            }
        );

        return url;
    };

    /*
     * Menyesuaikan checkbox berdasarkan URL,
     * digunakan saat browser back/forward.
     */
    const syncFilterFromUrl = (url) => {
        const selectedCompanies =
            url.searchParams.getAll(
                "company[]"
            );

        companyCheckboxes.forEach(
            (checkbox) => {
                checkbox.checked =
                    selectedCompanies.includes(
                        checkbox.value
                    );
            }
        );

        updateCompanyFilterLabel();
    };

    const loadResults = async (
        url,
        { updateHistory = true } = {}
    ) => {
        /*
         * Batalkan request sebelumnya jika user
         * mengganti keyword/filter dengan cepat.
         */
        activeRequest?.abort();

        const requestController =
            new AbortController();

        activeRequest = requestController;

        setLoading(true);

        try {
            const response = await fetch(url, {
                method: "GET",

                headers: {
                    Accept: "application/json",
                    "X-Requested-With":
                        "XMLHttpRequest",
                },

                signal:
                    requestController.signal,
            });

            if (!response.ok) {
                throw new Error(
                    `Request failed: ${response.status}`
                );
            }

            const data = await response.json();

            results.innerHTML = data.html;

            if (
                statistics &&
                typeof data.statisticsHtml === "string"
            ) {
                statistics.innerHTML =
                    data.statisticsHtml;
}

            if (status) {
                status.textContent =
                    `${data.total} employee found / ` +
                    `${data.total} employee ditemukan`;
            }

            if (updateHistory) {
                window.history.replaceState(
                    {},
                    "",
                    url
                );
            }

            resetButton?.classList.toggle(
                "hidden",
                input.value.trim() === ""
            );
        } catch (error) {
            if (error.name === "AbortError") {
                return;
            }

            console.error(
                "Employee search/filter failed:",
                error
            );

            if (status) {
                status.textContent =
                    "Search failed / Pencarian gagal";
            }
        } finally {
            /*
             * Request lama tidak boleh mematikan
             * indikator loading milik request terbaru.
             */
            if (
                activeRequest ===
                requestController
            ) {
                setLoading(false);
                activeRequest = null;
            }
        }
    };

    /*
     * Live search setelah user berhenti mengetik.
     */
    input.addEventListener("input", () => {
        window.clearTimeout(
            debounceTimer
        );

        debounceTimer =
            window.setTimeout(() => {
                loadResults(
                    createSearchUrl()
                );
            }, 350);
    });

    /*
     * Tombol search.
     */
    form.addEventListener(
        "submit",
        (event) => {
            event.preventDefault();

            window.clearTimeout(
                debounceTimer
            );

            loadResults(
                createSearchUrl()
            );
        }
    );

    /*
     * Reset search saja.
     * Company yang dipilih tetap dipertahankan.
     */
    resetButton?.addEventListener(
        "click",
        (event) => {
            event.preventDefault();

            input.value = "";
            input.focus();

            loadResults(
                createSearchUrl()
            );
        }
    );

    /*
     * Jalankan filter ketika checkbox berubah.
     */
    companyCheckboxes.forEach(
        (checkbox) => {
            checkbox.addEventListener(
                "change",
                () => {
                    window.clearTimeout(
                        debounceTimer
                    );

                    updateCompanyFilterLabel();

                    loadResults(
                        createSearchUrl()
                    );
                }
            );
        }
    );

    /*
     * Reset semua company.
     * Keyword pencarian tetap dipertahankan.
     */
    companyFilterClear?.addEventListener(
        "click",
        () => {
            companyCheckboxes.forEach(
                (checkbox) => {
                    checkbox.checked = false;
                }
            );

            updateCompanyFilterLabel();

            loadResults(
                createSearchUrl()
            );
        }
    );

    /*
     * Pagination dijalankan melalui AJAX.
     */
    results.addEventListener(
        "click",
        (event) => {
            const link =
                event.target.closest(
                    "a[href]"
                );

            if (
                !link ||
                !results.contains(link)
            ) {
                return;
            }

            const url = new URL(
                link.href
            );

            if (
                url.origin !==
                window.location.origin
            ) {
                return;
            }

            event.preventDefault();

            loadResults(url);

            results.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
        }
    );

    /*
     * Mendukung browser back/forward.
     */
    window.addEventListener(
        "popstate",
        () => {
            const url = new URL(
                window.location.href
            );

            input.value =
                url.searchParams.get(
                    "search"
                ) ?? "";

            syncFilterFromUrl(url);

            loadResults(url, {
                updateHistory: false,
            });
        }
    );

    /*
     * Menampilkan jumlah filter saat halaman
     * pertama kali dibuka.
     */
    updateCompanyFilterLabel();
});