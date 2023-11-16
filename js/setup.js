import PackageJson from '~/package.json'
import Functions from '~/js/Functions'
export default {
  /**
   * いい感じのタイトルを付ける
   * @param {string} newTitle 新しく付けたいタイトル
   * @returns 引数に合わせて設定したら0、デフォルトのまま設定したら1
   */
  setTitle: (newTitle) => {
    let siteName = PackageJson.name
    siteName = Functions.ifEnglishStartUpper(siteName)
    let pageTitle
    let returnCode
    if (newTitle) {
      pageTitle = `${newTitle} | ${siteName}`
      returnCode = 0
    } else {
      pageTitle = siteName
      returnCode = 1
    }
    useServerHead({
      title: pageTitle,
    })
    return returnCode
  },
}
