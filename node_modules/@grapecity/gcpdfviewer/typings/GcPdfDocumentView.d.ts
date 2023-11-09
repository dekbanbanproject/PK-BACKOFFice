//@ts-ignore
import { PluginModel, Store } from "@grapecity/viewer-core";
import { GcPdfSearcher } from './Search/GcPdfSearcher';
import { FindOptions, SearchResult } from "./Search/types";
export declare class ReportPage implements PluginModel.IPageData {
    readonly pageIndex: number;
    readonly pageSize: PluginModel.PageSize;
    constructor(pageIndex: number, pageSize: PluginModel.PageSize);
}
export declare class GcPdfDocumentView implements PluginModel.IDocumentView {
    readonly docViewer: any;
    private readonly _searcher;
    private _outlineStore;
    private _articlesStore;
    constructor(docViewer: any, _searcher: GcPdfSearcher);
    get pageCount(): PluginModel.PageCountResult;
    get outlineStore(): Store<any, any>;
    get articlesStore(): Store<any, any>;
    awaitPage: (index: number) => Promise<ReportPage>;
//@ts-ignore
    search: (options: FindOptions, _startFrom: any) => AsyncIterableIterator<SearchResult>;
}
