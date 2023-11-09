import * as SearchPanel from './types';
//@ts-ignore
import { CancellationToken, PluginModel } from '@grapecity/viewer-core';
export declare enum SearchStatus {
    Completed = "completed",
    Cancelled = "cancelled",
    ArgumentError = "argerror"
}
export declare function search(view: PluginModel.IDocumentView | null, searchOptions: SearchPanel.FindOptions, startFrom: SearchPanel.SearchResult, resultFn: (result: SearchPanel.SearchResult) => void, progressFn?: (progress: {
    pageIndex: number;
    pageCount: number | null;
}) => void, cancel?: CancellationToken): Promise<SearchStatus>;
