import { ArticlesMsg, ArticlesModel } from './types';
export declare const init: () => ArticlesModel;
declare const reducer: (message: ArticlesMsg, model: ArticlesModel) => ArticlesModel;
export { reducer as update };
