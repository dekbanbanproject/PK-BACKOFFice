export declare function findBaseScriptUrl(): string;
export declare function makeAbsoluteUrl(relativeUrl: string, baseUrl?: string): string;
export declare function getFilenameFromUrl(url: string): string;
export declare function removeNullCharacters(str: any): any;
export declare enum LinkTarget {
    NONE = 0,
    SELF = 1,
    BLANK = 2,
    PARENT = 3,
    TOP = 4
}
export declare function addLinkAttributes(link: HTMLAnchorElement, params: {
    url: string;
    target: LinkTarget;
    rel: string;
}): void;
export declare function createObjectURL(data: any, contentType: string, forceDataSchema?: boolean): string;
