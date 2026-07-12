document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(
        "[data-employee-search-form]"
    );

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

    const loadingIndicator = document.querySelector(
        "[data-employee-search-loading]"
    );

    const status = document.querySelector(
        "[data-employee-search-status]"
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
            loadingIndicator.style.display = isLoading
                ? "flex"
                : "none";
        }

        input.classList.toggle("pr-11", isLoading);
    };

    const createSearchUrl = () => {
        const url = new URL(
            form.action,
            window.location.origin
        );

        const keyword = input.value.trim();

        if (keyword !== "") {
            url.searchParams.set("search", keyword);
        }

        return url;
    };

    const loadResults = async (
        url,
        { updateHistory = true } = {}
    ) => {
        /*
         * Batalkan request sebelumnya jika user
         * mengetik keyword baru dengan cepat.
         */
        activeRequest?.abort();

        activeRequest = new AbortController();

        setLoading(true);

        try {
            const response = await fetch(url, {
                method: "GET",

                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },

                signal: activeRequest.signal,
            });

            if (!response.ok) {
                throw new Error(
                    `Request failed: ${response.status}`
                );
            }

            const data = await response.json();

            results.innerHTML = data.html;

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
                "Employee live search failed:",
                error
            );

            if (status) {
                status.textContent =
                    "Search failed / Pencarian gagal";
            }
        } finally {
            setLoading(false);
            activeRequest = null;
        }
    };

    /*
     * Search berjalan 350 ms setelah
     * employee berhenti mengetik.
     */
    input.addEventListener("input", () => {
        window.clearTimeout(debounceTimer);

        debounceTimer = window.setTimeout(() => {
            loadResults(createSearchUrl());
        }, 350);
    });

    /*
     * Tombol Search tetap dapat digunakan.
     */
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        window.clearTimeout(debounceTimer);

        loadResults(createSearchUrl());
    });

    /*
     * Reset tanpa reload halaman.
     */
    resetButton?.addEventListener("click", (event) => {
        event.preventDefault();

        input.value = "";
        input.focus();

        loadResults(
            new URL(
                form.action,
                window.location.origin
            )
        );
    });

    /*
     * Pagination juga dijalankan melalui AJAX.
     */
    results.addEventListener("click", (event) => {
        const link = event.target.closest("a[href]");

        if (!link || !results.contains(link)) {
            return;
        }

        const url = new URL(link.href);

        if (url.origin !== window.location.origin) {
            return;
        }

        event.preventDefault();

        loadResults(url);

        results.scrollIntoView({
            behavior: "smooth",
            block: "start",
        });
    });

    /*
     * Mendukung tombol browser back dan forward.
     */
    window.addEventListener("popstate", () => {
        const url = new URL(window.location.href);

        input.value =
            url.searchParams.get("search") ?? "";

        loadResults(url, {
            updateHistory: false,
        });
    });
});