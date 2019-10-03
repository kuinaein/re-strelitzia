/**
 * @param {Object<string, any>} constants 定数のキーと実態
 */
export function mapConstants(constants) {
  return Object.keys(constants).reduce((result, k) => {
    result[k] = () => constants[k];
    return result;
  }, {});
}
