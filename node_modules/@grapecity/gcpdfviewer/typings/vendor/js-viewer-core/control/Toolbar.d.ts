import { ToolbarItem, ToolbarLayout, ToolbarView } from '../components/Toolbar';
export { ToolbarItem, ToolbarLayout };
export declare class Toolbar {
    private _view;
    constructor(_view: ToolbarView);
    addItem(item: ToolbarItem): void;
    updateItem(key: string, itemUpdate?: Partial<ToolbarItem>): void;
    removeItem(key: string): void;
    updateLayout(layout: ToolbarLayout): void;
    getDefaultToolbarItems(): string[];
    recalculateBounds: () => void;
}
