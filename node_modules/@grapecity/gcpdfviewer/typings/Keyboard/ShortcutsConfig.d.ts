import { ViewerOptions } from "../ViewerOptions";
export declare class ShortcutsConfig {
    private options;
    private readonly _commonkeyCodes;
    constructor(options: ViewerOptions);
    populateOptions(opts: ViewerOptions): void;
    getShortcutTip(toolName: string): string;
    private _parseShortcutKeys;
    private _readShortcutTooltipPart;
}
