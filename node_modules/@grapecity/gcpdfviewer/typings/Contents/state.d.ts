import { ContentsMsg, ContentsModel } from './types';
export declare const init: () => ContentsModel;
declare const reducer: (message: ContentsMsg, model: ContentsModel) => ContentsModel;
export { reducer as update };
