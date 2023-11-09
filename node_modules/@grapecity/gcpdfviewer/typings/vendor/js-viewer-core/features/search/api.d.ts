import { CancellationToken } from "../../control/CancellationToken";
import * as SearchPanel from './types';
import { IDocumentView } from "../../api/PluginModel";
export declare enum SearchStatus {
    Completed = "completed",
    Cancelled = "cancelled",
    ArgumentError = "argerror"
}
export declare function search(view: IDocumentView | null, searchOptions: SearchPanel.FindOptions, startFrom: SearchPanel.SearchResult, resultFn: (result: SearchPanel.SearchResult) => void, progressFn?: (progress: {
    pageIndex: number;
    pageCount: number | null;
}) => void, cancel?: CancellationToken): Promise<SearchStatus>;
