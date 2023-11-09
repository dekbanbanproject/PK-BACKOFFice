import { SearchResult, FindOptions } from "./types";
import PdfReportPlugin from "../plugin";
//@ts-ignore
import { PluginModel, SearchFeature } from "@grapecity/viewer-core";
export declare type GcPdfSearcherOptions = {
    query: string;
    phraseSearch: boolean;
    caseSensitive: boolean;
    entireWord: boolean;
    findPrevious: boolean;
    startsWith: boolean;
    endsWith: boolean;
    wildcards: boolean;
    proximity: boolean;
    highlightAll: boolean;
};
export declare class GcPdfSearcher {
    private readonly _plugin;
    private _cancellation?;
    private _extractTextPromises;
    private _normalizedQuery;
    private _pageContents;
    private _pageContentsEndings;
    private _pageMatches;
    private _pageMatchesLength;
    private _pageAcroFormResults;
    private _pagesCount;
    private _pdfDocument;
    private _rawQuery;
    private _state;
    private _totalResultsCount;
    private _totalResultsCountPromise;
    private _firstSearchResult?;
    private _selectedSearchResult?;
    constructor(_plugin: PdfReportPlugin);
    toggle(): void;
    close(): void;
    updateUIState(state: any, previous: any, matchesCount: any): void;
    private nextSearchResult;
    isResultSelected(result: SearchResult): boolean;
    get state(): GcPdfSearcherOptions;
    get highlightAll(): boolean;
    set highlightAll(checked: boolean);
    get findController(): any;
    applyHighlight(): void;
    resetResults(): void;
    updateAllPages(): void;
    get totalResultsCount(): number;
    get totalResultsCountPromise(): Promise<number> | null;
//@ts-ignore
    search(options: FindOptions): AsyncIterableIterator<SearchResult>;
    highlight(searchResult: SearchFeature.SearchResult, pageIndex?: number): void;
    cancel(): void;
    _initialize(): void;
    get _query(): string;
    _extractText(): void;
    _reset(): void;
    _prepareMatches(matchesWithLength: any, matches: any, matchesLength: any): void;
    _isEntireWord(content: any, lineEndings: {
        [x: number]: boolean;
    }, startIdx: any, length: any): boolean;
    _isStartsWith(content: any, lineEndings: {
        [x: number]: boolean;
    }, startIdx: any, _length: any): boolean;
    _isEndsWith(content: any, lineEndings: {
        [x: number]: boolean;
    }, startIdx: any, length: any): boolean;
    _findPhraseMathIndex(pageContent: string, query: string, startIndex: number, lineEndings: {
        [x: number]: boolean;
    }): {
        matchIdx: number;
        queryLen: number;
    } | null;
    _calculatePhraseMatch(query: string, pageContentsEndings: {
        [x: number]: boolean;
    }, pageContent: string, entireWord: boolean, startsWith: boolean, endsWith: boolean, wildcards: boolean): {
        matches: number[];
        matchesLength: number[];
    };
    findLineEndIndex(startIndex: number, pageContent: string, lineEndings: {
        [x: number]: boolean;
    }): number;
    _calculateWordMatch(query: string, pageContentsEndings: {
        [x: number]: boolean;
    }, pageContent: any, entireWord: any, startsWith: any, endsWith: any, wildcards: any): {
        matches: number[];
        matchesLength: number[];
    };
    _calculateMatch(pageContent: string, pageContentsEndings: {
        [x: number]: boolean;
    }): {
        matches: number[];
        matchesLength: number[];
    };
    _proximityCheckAllWordsInResults(queryArray: RegExpMatchArray, matchIndicesToKeep: {
        matchIndex: number;
        matchLength: number;
    }[], pageContent: string): boolean;
    _countWordsBetweenTerms(startInd: number, endInd: number, pageContent: string): number;
    _findMathIndexByQuery(pageContent: string, mathes: number[], mathesLength: number[], subquery: string, startIndex: number): {
        mathIndex: number;
        mathLength: number;
    };
    renderHighlightPage(page: PluginModel.IPageData, results: SearchFeature.SearchResult[]): PluginModel.PageView;
}
